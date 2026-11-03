<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\Auth\NewUserWelcomeMail;
use App\Models\Auth\UserLoginRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;





class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless() // avoids session issues
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Create user if not exists
        $user = User::firstOrCreate(
            ['email1' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'account_type' => 3,
                'preferred_language' => app()->getLocale() ?: 'en',
                'first_name' => $googleUser->user['given_name'] ?? null,
                'last_name' => $googleUser->user['family_name'] ?? null,
                'email1_verified_at' => Carbon::now(),
                'email1_verification' => 'verified',
            ]
        );

        // Send welcome email if newly created
        if ($user->wasRecentlyCreated) {
            Mail::to($user->email1)->send(new NewUserWelcomeMail($user));
        }

        // Log the user in
        Auth::login($user, true);

        // Record login info
        $currentSessionId = Session::getId();
        $now = Carbon::now();
        $ipAddress = request()->ip();
        $userAgent = request()->header('User-Agent');

        // Determine device type
        if (preg_match('/Mobile|Android|iP(hone|od|ad)|Opera Mini/i', $userAgent)) {
            $deviceType = 'Mobile';
        } elseif (preg_match('/Tablet|iPad/i', $userAgent)) {
            $deviceType = 'Tablet';
        } else {
            $deviceType = 'Desktop';
        }

        $loginRecord = new UserLoginRecord();
        $loginRecord->username = $user->username;
        $loginRecord->session_id = $currentSessionId;
        $loginRecord->logged_in_at = $now;
        $loginRecord->ip_address = $ipAddress;
        $loginRecord->device_type = $deviceType;
        $loginRecord->mfa_verified_at = Carbon::now();
        $loginRecord->save();

        // Handle MFA if required
        if ($user->require_login_mfa == 1) {
            DB::table('sessions')->where('id', $currentSessionId)->update(['mfa_verified' => 1]);
        }

        // Redirect to dashboard using AuthController
        $authController = new AuthController();
        return $authController->redirectToUserDashboard($user->account_type);
    }
}
