<?php

use App\Models\Config\BasicSystemSetting;
use App\Models\Config\HomepageSlider;
use App\Models\Config\Language;
use App\Models\Config\WhatsappSupportLine;
use App\Models\Payment\BankAccount;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Products\QuoteRequest;
use App\Services\Translator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;





if (!function_exists('getSystenInfo')) {
    function getSystemInfo()
    {
        return [
            'product_name' => 'producthub',
            'description' => 'Product Hub',
            'product_id' => 'P7IKDR456HB',
            'sub_product_name' => 'producthub',
            'product_key' => 'I99XFJ82DF',
            'system_type' => 'Web Script',
            'version_number' => '0.1',

            'requirements' => [
                'php_version' => 8.2,
                'mysql_version' => 10,
            ],

        ];
    }
}

if (!function_exists('getUserType')) {
    function getUserType()
    {
        if (!Auth::check()) {
            return 'guest';
        }

        return match ((int) Auth::user()->account_type) {
            0 => 'root',
            1 => 'admin',
            2 => 'staff',
            3 => 'customer',
            default => 'guest',
        };
    }
}


if (!function_exists('t')) {
    function t(string $key, array $replace = [])
    {
        return Translator::get($key, $replace);
    }
}

if (!function_exists('activeLanguages')) {
    function activeLanguages()
    {
        return Language::where('status', 'active')->get();
    }
}
if (!function_exists('currentLocale')) {
    function currentLocale()
    {
        return app()->getLocale();
    }
}


if (!function_exists('systemLogo')) {
    function systemLogo()
    {
        $defaultSettings = BasicSystemSetting::first();

        if (
            $defaultSettings &&
            $defaultSettings->logo &&
            file_exists(
                public_path(
                    'system/images/' .
                        $defaultSettings->logo,
                ),
            )
        ) {
            $logo = asset('system/images/' . $defaultSettings->logo);
        } else {
            $logo = null;
        }
        return $logo;
    }
}

if (!function_exists('systemWideLogo')) {
    function systemWideLogo()
    {
        $defaultSettings = BasicSystemSetting::first();

        if (
            $defaultSettings &&
            $defaultSettings->logo_full &&
            file_exists(
                public_path(
                    'system/images/' .
                        $defaultSettings->logo_full,
                ),
            )
        ) {
            $logoFull = asset('system/images/' . $defaultSettings->logo_full);
        } else {
            $logoFull = null;
        }
        return $logoFull;
    }
}

if (!function_exists('homepageSliders')) {
    function homepageSliders()
    {
        return HomepageSlider::orderBy('order')->get();
    }
}

if (!function_exists('activeCategories')) {
    function activeCategories()
    {
        return Category::where('is_active', true)->orderBy('category')->get();
    }
}

if (!function_exists('featuredCategories')) {
    function featuredCategories()
    {
        return Category::where('is_active', true)->where('is_featured', true)->orderBy('category')->get();
    }
}

if (!function_exists('allCategories')) {
    function allCategories()
    {
        return Category::where('is_active', true)->orderBy('category')->get();
    }
}


if (!function_exists('featuredProducts')) {
    function featuredProducts()
    {
        return Product::where('is_active', true)->where('is_featured', true)->orderBy('name')->get();
    }
}

if (!function_exists('displayPrice')) {
    function displayPrice()
    {
        static $displayPrice = null;

        if ($displayPrice === null) {
            $basicSetup = BasicSystemSetting::first();
            $displayPrice = $basicSetup->display_price ?? false;
        }

        return $displayPrice;
    }
}

if (!function_exists('routePrefix')) {
    function routePrefix()
    {
        $type = getUserType();

        return in_array($type, ['customer', 'guest']) ? $type : null;
    }
}

if (!function_exists('currentSession')) {

    function currentSession()
    {
        $currentSessionId = Session::getId();

        $currentSession = DB::table('sessions')
            ->where('id', $currentSessionId)
            ->first();

        if (!$currentSession) {

            $currentSession = (object) [
                'id' => $currentSessionId,
                'user_id' => Auth::id(), // null if guest
                'mfa_verified' => Session::get('mfa_verified', 0),
                'login_otp' => Session::get('login_otp'),
                'login_otp_expires_at' => Session::get('login_otp_expires_at'),
            ];
        }

        return $currentSession;
    }
}


if (!function_exists('draftedQuote')) {
    function draftedQuote()
    {
        $user = Auth::user();
        if ($user) {
            $draftedRequest = QuoteRequest::where('username', $user->username)
                ->where('status', 'draft')
                ->first();
        } else {
            $draftedRequest = QuoteRequest::where('session_id', currentSession()->id)
                ->where('status', 'draft')
                ->first();
        }
        return $draftedRequest;
    }
}

if (!function_exists('submittedQuoteRequests')) {
    function submittedQuoteRequests()
    {
        $submittedQuotes = QuoteRequest::where('status', 'submitted')
            ->get();
        return $submittedQuotes;
    }
}

if (!function_exists('activeWhatsAppSupportLines')) {
    function activeWhatsAppSupportLines()
    {
        return WhatsappSupportLine::where('status', 'active')->orderBy('name')->get();
    }
}


if (!function_exists('authUser')) {
    function authUser()
    {
        return Auth::user();
    }
}


if (!function_exists('businessAddress')) {
    function businessAddress()
    {
        static $displayPrice = null;

        $basicSetup = BasicSystemSetting::first();
        $address = $basicSetup->address ?? false;

        return $address;
    }
}

if (!function_exists('businessPhone')) {
    function businessPhone()
    {
        $basicSetup = BasicSystemSetting::first();
        $phone = $basicSetup->phone ?? false;

        return $phone;
    }
}


if (!function_exists('businessEmail')) {
    function businessEmail()
    {
        $basicSetup = BasicSystemSetting::first();
        $email = $basicSetup->email ?? false;

        return $email;
    }
}


if (!function_exists('activeBankAccounts')) {
    function activeBankAccounts()
    {
        return BankAccount::where('is_active', true)->orderBy('bank_name')->get();
    }
}
