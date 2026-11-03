<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\Auth\LoginOtpMail;
use Illuminate\Support\Facades\DB;
use App\Mail\Auth\PasswordResetMail;
use App\Models\Auth\UserLoginRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Auth\NewUserWelcomeMail;
use Illuminate\Support\Facades\Session;
use App\Mail\Auth\EmailVerificationMail;
use App\Models\Config\BasicSystemSetting;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function redirectToUserDashboard($accountType)
    {
        if (!Auth::check()) {
            return $this->accessDeniedLogout();
        }

        switch (getUserType()) {
            case 'root':
            case 'admin':
            case 'staff':
                return redirect()->route('adminDashboard');

            case 'customer':
                // return redirect()->route('customerDashboard');
                return redirect()->route('customerHomepage');

            default:
                return $this->accessDeniedLogout();
        }
    }
    public function accessDeniedLogout()
    {
        // Get current session ID
        $currentSessionId = Session::getId();
        Auth::logout();

        // Invalidate and regenerate the session token
        Session::invalidate();
        Session::regenerateToken();


        // Check if session driver is 'database'
        if (config('session.driver') === 'database') {
            $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();

            // If the session exists, delete it from the database
            if ($currentSession) {
                DB::table('sessions')->where('id', $currentSession->id)->delete();
            }
        }

        return redirect(url('/'))->with('error', t('notifications.accessDenied'));
    }
    public function login()
    {
        if (!empty(Auth::check() || getUserType() !== 'guest')) {
            return $this->redirectToUserDashboard(Auth::user()->account_type);
        }
        return view('utilities.pages.auth.login');
    }
    public function register()
    {
        if (!empty(Auth::check() || getUserType() !== 'guest')) {
            return $this->redirectToUserDashboard(Auth::user()->account_type);
        }
        return view('utilities.pages.auth.register');
    }
    public function submitUserRegistrationRequest(Request $request)
    {
        $passwordRules = [
            'required',
            'string',
            'min:8', // Minimum of 8 characters
            'regex:/[A-Z]/', // At least 1 uppercase letter
            'regex:/[a-z]/', // At least 1 lowercase letter
            'regex:/[0-9]/', // At least 1 number
            'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least 1 special character
        ];

        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'unique:users,username',
                'regex:/^\S*$/u',           // No spaces allowed
                'max:8',                    // Maximum 8 characters
                'not_regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/',  // No email format allowed
                'not_regex:/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}]/u',  // No emoji or symbols
            ],
            'email' => 'required|email|unique:users,email1|unique:users,email2',
            'phone' => 'nullable|string|unique:users,phone1|unique:users,phone2',
            'password' => $passwordRules,
            'confirm_password' => 'required|string|same:password',
        ], [
            // Custom error messages
            'username.required' => t('auth.commonValidationRules.usernameRequired'),
            'username.unique' => t('auth.commonValidationRules.usernameTaken'),
            'username.regex' => t('auth.commonValidationRules.usernameNoSpace'),
            'password.required' => t('auth.commonValidationRules.passwordRequired'),
            'password.min' => t('auth.commonValidationRules.passwordMin'),
            'password.regex' => t('auth.commonValidationRules.passwordMustContain'),
            'confirm_password.same' => t('auth.commonValidationRules.passwordMatchError'),
        ]);

        // Check validation results
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // Pass all validation errors
                ->withInput(); // Retain user input
        }


        $user = new User();
        $user->username = trim($request->username);
        $user->email1 = trim($request->email);
        $user->phone1 = trim($request->phone);
        $user->account_type = 3;
        $user->password = Hash::make($request->password);
        $user->preferred_language = app()->getLocale() ?: 'en';


        $user->save();

        Mail::to($user->email1)->send(new NewUserWelcomeMail($user));

        return redirect()->route('loginPage')->with('success', t('notifications.done'));
    }
    public function authLogin(Request $request)
    {
        $request->validate([
            'login_id' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $request->login_id, 'password' => $request->password]) || Auth::attempt(['email1' => $request->login_id, 'password' => $request->password]) || Auth::attempt(['email2' => $request->login_id, 'password' => $request->password])) {
            $user = Auth::user();
            $currentSessionId = Session::getId();
            $now = Carbon::now();
            $ipAddress = $request->ip();
            $userAgent = $request->header('User-Agent');

            // Determine device type
            if (preg_match('/Mobile|Android|iP(hone|od|ad)|Opera Mini/i', $userAgent)) {
                $deviceType = 'Mobile';
            } elseif (preg_match('/Tablet|iPad/i', $userAgent)) {
                $deviceType = 'Tablet';
            } else {
                $deviceType = 'Desktop';
            }

            if ($user) {
                $loginRecord = new UserLoginRecord();
                $loginRecord->username = $user->username;
                $loginRecord->session_id = $currentSessionId;
                $loginRecord->logged_in_at = $now;
                $loginRecord->ip_address = $ipAddress;
                $loginRecord->device_type = $deviceType;
                $loginRecord->save();


                if ($user->require_login_mfa == 1) {
                    return redirect()->route('authenticateLogin');
                } else {
                    return $this->redirectToUserDashboard($user->account_type);
                }
            } else {
                return redirect()->route('AuthLogout');
            }
            return $this->redirectToUserDashboard($user->account_type);
        } else {
            return redirect()->back()->with('error', t('notifications.invalidCredentials'));
        }
    }
    public function AuthLogout()
    {
        // Get current session ID
        $currentSessionId = Session::getId();
        $loginRecord = UserLoginRecord::where('session_id', $currentSessionId)->first();
        $now = Carbon::now();

        if ($loginRecord) {
            $loginRecord->logged_out_at = $now;
            $loginRecord->save();
        }

        Auth::logout();

        // Invalidate and regenerate the session token
        Session::invalidate();
        Session::regenerateToken();


        // Check if session driver is 'database'
        if (config('session.driver') === 'database') {
            $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();

            // If the session exists, delete it from the database
            if ($currentSession) {
                DB::table('sessions')->where('id', $currentSession->id)->delete();
            }
        }

        return redirect(url('/'))->with('success', t('notifications.loggedOut'));
    }
    public function forgotPassword()
    {
        if (!empty(Auth::check())) {
            return $this->redirectToUserDashboard(Auth::user()->account_type);
        }
        return view('utilities.pages.auth.fogortPassword');
    }

    public function requestPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email1',
        ]);

        $user = User::getEmailCheck($request->email);
        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            $user->save();
            Mail::to($user->email1)->send(new PasswordResetMail($user));

            return redirect()->route('loginPage')->with('success', t('notifications.emailSent'));
        } else {
            return redirect()->back()->with('error', t('notifications.noRecordsFound'));
        }
    }

    public function passwordResetPage($remember_token)
    {
        $user = User::getTokenSingle($remember_token);
        if (!empty($user)) {
            $data['user'] = $user;
            return view('utilities.pages.auth.resetPassword', $data);
        } else {
            abort(404);
        }
    }

    public function submitPasswordResetRequest($token, Request $request)
    {
        $passwordRules = [
            'required',
            'string',
            'min:8', // Minimum of 8 characters
            'regex:/[A-Z]/', // At least 1 uppercase letter
            'regex:/[a-z]/', // At least 1 lowercase letter
            'regex:/[0-9]/', // At least 1 number
            // 'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least 1 special character
        ];

        // Validate input
        $validator = Validator::make($request->all(), [
            'password' => $passwordRules,
            'confirm_password' => 'required|string|same:password',
        ]);

        // Check validation results
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // Pass all validation errors
                ->withInput(); // Retain user input
        }

        if ($request->password == $request->confirm_password) {
            $user = User::getTokenSingle($token);
            if (!$user) {
                return redirect()->back()->with('error', t('notifications.userNotFound'));
            }

            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();

            return redirect()->route('loginPage')->with('success', t('notifications.done'));
        } else {
            return redirect()->back()->with('error', t('notifications.passwordDoNotMatch'));
        }
    }
    public function maintenanceMode()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->accessDeniedLogout();
        }
        $basicDefaultSettings = BasicSystemSetting::first();
        if ($maintenanceModeLock = $basicDefaultSettings->maintenance_mode == true && (int) $user->account_type !== 0) {
            return view('utilities.pages.auth.maintenanceMode');
        }
        if (!empty(Auth::check())) {
            return $this->redirectToUserDashboard(Auth::user()->account_type);
        }
    }
    public function generateEmailVerificationCode(User $user)
    {
        $otp = rand(1000, 9999);

        DB::table('users')
            ->where('username', $user->username)
            ->update([
                'email1_verification_code' => $otp,
                'email1_verification_code_expires_at' => now()->addMinutes(10),
            ]);

        return $otp;
    }

    public function resetEmailVerificationCode(User $user)
    {
        DB::table('users')
            ->where('username', $user->username)
            ->update([
                'email1_verification_code' => null,
                'email1_verification_code_expires_at' => null,
            ]);
    }
    private function sendEmailVerificationCodeEmailtoUser($email1, $otp)
    {
        // Send OTP to user's email
        $user = User::where('email1', $email1)->first();
        if (!$user) {
            return redirect()->back()->with('error', t('notifications.error'));
        } else {
            Mail::to($email1)->send(new EmailVerificationMail($email1, $user, $otp));
        }
    }
    public function verifyUserEmail()
    {
        $user = Auth::user();
        if (
            $user->email1_verification !== 'verified'
        ) {
            if (
                $user->email1_verification_code === null ||
                $user->email1_verification_code === '' ||
                Carbon::parse($user->email1_verification_code_expires_at)->lt(now())
            ) {
                $otp = $this->generateEmailVerificationCode($user);
                $this->sendEmailVerificationCodeEmailtoUser($user->email1, $otp);
            }

            return view('utilities.pages.auth.userAccountValidation');
        } else {
            return $this->redirectToUserDashboard($user->account_type);
        }
    }
    public function resendEmailVerificationCodeEmailtoUser()
    {
        $user = Auth::user();

        if ($user) {
            $otp = $this->generateEmailVerificationCode($user);
            $this->sendEmailVerificationCodeEmailtoUser($user->email1, $otp);

            return redirect()->back()->with('success', t('notifications.emailSent'));
        } else {
            return $this->accessDeniedLogout();
        }
    }
    public function verifyUserEmailSubmit(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric|digits:4',
        ]);
        $user = Auth::user();
        $userRec = User::where('username', $user->username);


        if ($user) {
            // Check if OTP has expired
            if ($user->email1_verification_code_expires_at < now()) {
                $this->resetEmailVerificationCode($user);
                return $this->accessDeniedLogout();
            }

            // check if OTP is correct
            if ($request->input('verification_code') === $user->email1_verification_code) {
                $this->resetEmailVerificationCode($user);


                $userRec->update([
                    'email1_verification' => 'verified',
                    'email1_verified_at' => now(),
                ]);

                return $this->redirectToUserDashboard(Auth::user()->account_type);
            } else {
                return redirect()->back()->with('error', t('notifications.invalidCode'));
            }
        } else {
            return $this->accessDeniedLogout();
        }
    }

    public function generateTwoFactorCode(User $user, $currentSession)
    {
        $otp = rand(100000, 999999);

        DB::table('sessions')
            ->where('id', $currentSession->id)
            ->update([
                'login_otp' => $otp,
                'login_otp_expires_at' => now()->addMinutes(10),
            ]);

        return $otp;
    }

    public function resetTwoFactorCode(User $user, $currentSession)
    {
        DB::table('sessions')
            ->where('id', $currentSession->id)
            ->update([
                'login_otp' => null,
                'login_otp_expires_at' => null,
            ]);
    }


    private function sendLoginOtpByEmailtoUser($email1, $otp)
    {
        // Send OTP to user's email
        $user = User::where('email1', $email1)->first();
        if (!$user) {
            return redirect()->back()->with('error', t('notifications.error'));
        } else {
            // Okay but temporarily disabled
            Mail::to($email1)->send(new LoginOtpMail($email1, $user, $otp));
        }
    }
    public function loginOtpExpiredLogout()
    {
        // Get current session ID
        $currentSessionId = Session::getId();
        $now = Carbon::now();

        Auth::logout();

        // Invalidate and regenerate the session token
        Session::invalidate();
        Session::regenerateToken();


        // Check if session driver is 'database'
        if (config('session.driver') === 'database') {
            $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();

            // If the session exists, delete it from the database
            if ($currentSession) {
                DB::table('sessions')->where('id', $currentSession->id)->delete();
            }
        }

        return redirect(url('/'))->with('error', t('notifications.otpExpired'));
    }
    public function authenticateLogin()
    {
        $user = Auth::user();

        if (!$user) {
            return $this->accessDeniedLogout();
        }

        $currentSessionId = Session::getId();
        $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();

        if (!$currentSession) {
            return $this->accessDeniedLogout();
        }

        if ((int) $user->require_login_mfa === 0) {
            return $this->redirectToUserDashboard($user->account_type);
        }

        // MFA verified
        if ((int) $currentSession->mfa_verified === 1) {
            return $this->redirectToUserDashboard($user->account_type);
        }


        // OTP to email senabled and not yet verified
        if (
            (int) $user->require_login_mfa === 1
            && (int) $currentSession->mfa_verified !== 1
        ) {

            if (
                $currentSession->login_otp === null ||
                $currentSession->login_otp === '' ||
                Carbon::parse($currentSession->login_otp_expires_at)->lt(now())
            ) {
                $otp = $this->generateTwoFactorCode($user, $currentSession);
                $this->sendLoginOtpByEmailtoUser($user->email1, $otp);
            }
            return view('utilities.pages.auth.loginOtpAuthentication');
        }
    }

    public function verifyLoginOtpSubmit(Request $request)
    {
        $request->validate([
            'login_otp' => 'required|numeric|digits:6',
        ]);
        $user = Auth::user();
        $currentSessionId = Session::getId();
        $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();

        if ($user) {
            // Check if OTP has expired
            if ($currentSession->login_otp_expires_at < now()) {
                $this->resetTwoFactorCode($user, $currentSession);
                return $this->loginOtpExpiredLogout();
            }

            // check if OTP is correct
            if ($request->input('login_otp') === $currentSession->login_otp) {
                DB::table('sessions')
                    ->where('id', $currentSession->id)
                    ->update([
                        'mfa_verified' => 1,
                    ]);
                $this->resetTwoFactorCode($user, $currentSession);

                return $this->redirectToUserDashboard(Auth::user()->account_type);
            } else {
                return redirect()->back()->with('error', t('notifications.incorrectOtp'));
            }
        } else {
            return $this->accessDeniedLogout();
        }
    }
    public function resendLoginOtp()
    {
        $user = Auth::user();
        $currentSessionId = Session::getId();
        $currentSession = DB::table('sessions')->where('id', $currentSessionId)->first();

        if ($user) {
            $otp = $this->generateTwoFactorCode($user, $currentSession);
            $this->sendLoginOtpByEmailtoUser($user->email1, $otp);

            return redirect()->back()->with('success', t('notifications.otpSent'));
        } else {
            return $this->accessDeniedLogout();
        }
    }
}
