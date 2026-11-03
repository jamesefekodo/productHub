<?php

namespace App\Http\Middleware\Auth;

use App\Models\Config\BasicSystemSetting;
use App\Models\Products\QuoteItem;
use App\Models\Products\QuoteRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;


class AuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $basicDefaultSettings = BasicSystemSetting::first();

            $accountType = (int) $user->account_type;
            $accountStatus = (int) $user->account_status;
            $maintenanceModeLock = $basicDefaultSettings->maintenance_mode == true && $accountType !== 0;

            $currentSessionId = Session::getId();
            $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();
            $userActiveSessions = DB::table('sessions')->where('user_id', $user->id)->count();

            if (!$currentSession) {
                $currentSession = (object) [
                    'id' => $currentSessionId,
                    'user_id' => $user->id,
                    'mfa_verified' => Session::get('mfa_verified', 0),
                    'login_otp' => Session::get('login_otp'),
                    'login_otp_expires_at' => Session::get('login_otp_expires_at'),
                ];
            }
            $userActiveSessions = DB::table('sessions')->where('user_id', $user->id)->count();

            // #####################################################################################################################
            // mode guest caart/quote to user
            $sessionId = session()->getId();

            //  Find guest draft quote
            $guestDraft = QuoteRequest::where('session_id', $sessionId)
                ->whereNull('username')
                ->where('status', 'draft')
                ->first();

            if ($guestDraft) {

                //  Check if user already has draft
                $userDraft = QuoteRequest::where('username', $user->username)
                    ->where('status', 'draft')
                    ->first();

                if ($userDraft) {
                    //  Merge items instead of overwriting

                    $guestItems = QuoteItem::where('quote_request_slug', $guestDraft->slug)->get();

                    foreach ($guestItems as $item) {

                        $existingItem = QuoteItem::where('quote_request_slug', $userDraft->slug)
                            ->where('product_slug', $item->product_slug)
                            ->where('variant_slug', $item->variant_slug)
                            ->first();

                        if ($existingItem) {
                            $existingItem->increment('quantity', $item->quantity);
                        } else {
                            $item->quote_request_slug = $userDraft->slug;
                            $item->save();
                        }
                    }

                    // delete guest draft
                    $guestDraft->delete();
                } else {
                    // simply assign draft to user
                    $guestDraft->update([
                        'username' => $user->username,
                        'session_id' => null
                    ]);
                }
            }

            // #########################################################################################################################

            // // checkpoints
            $maintenanceStatus = 'waiting';
            $emailVerificationStatus = 'waiting';
            $mfaVerificationStatus = 'waiting';

            // Maintenance mode
            if ($maintenanceModeLock === true) {
                if (
                    !Route::is('maintenanceMode') &&
                    !Route::is('logoutRequest')
                ) {
                    return redirect()->route('maintenanceMode');
                }
            } else {
                $maintenanceStatus = 'cleared';
            }

            // Account Email Verification
            if ($maintenanceStatus === 'cleared') {
                if ($user->email1_verification !== 'verified') {
                    if (
                        !Route::is('verifyUserEmail')
                        && !Route::is('logoutRequest')
                        && !Route::is('verifyUserEmailResendOtp')
                        && !Route::is('verifyUserEmailSubmit')
                    ) {
                        return redirect()->route('verifyUserEmail');
                    }
                } else {
                    $emailVerificationStatus = 'cleared';
                }
            }

            // Check Login Authentication
            if (
                $maintenanceStatus === 'cleared' &&
                $emailVerificationStatus === 'cleared'
            ) {

                // dd($currentSession);
                if ((int) $user->require_login_mfa === 1) {
                    if (
                        (int) $currentSession->mfa_verified !== 1
                        || $currentSession->login_otp !== null
                        || $currentSession->login_otp_expires_at !== null
                    ) {
                        if (
                            !Route::is('authenticateLogin')
                            && !Route::is('logoutRequest')
                            && !Route::is('resendLoginOtp')
                            && !Route::is('verifyLoginOtp')
                            && !Route::is('verifyLoginOtpSubmit')
                        ) {
                            return redirect()->route('authenticateLogin');
                        }
                    } else {
                        $mfaVerificationStatus = 'cleared';
                    }
                } else {
                    $mfaVerificationStatus = 'cleared';
                }
            }
        }

        return $next($request);
    }
}
