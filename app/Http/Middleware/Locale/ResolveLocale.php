<?php

namespace App\Http\Middleware\Locale;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ResolveLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Active languages from DB
        $supportedLocales = collect(activeLanguages())
            ->pluck('language_code')
            ->map(fn($code) => strtolower($code))
            ->toArray();

        $locale = null;

        // 1. Query param
        if ($request->filled('lang')) {
            $candidate = strtolower($request->query('lang'));
            if (in_array($candidate, $supportedLocales, true)) {
                $locale = $candidate;

                // Persist via cookie
                Cookie::queue('locale', $locale, 60 * 24 * 365);
                if (Auth::user()) {
                    $authUser = User::where('username', Auth::user()->username);
                    if ($authUser) {
                        $authUser->update(
                            [
                                'preferred_language' => $candidate,
                            ]
                        );
                    }
                }
            }
        }

        // 2. Auth user default
        if (!$locale && Auth::check() && in_array(Auth::user()->preferred_language ?? '', $supportedLocales, true)) {
            $locale = Auth::user()->preferred_language;
        }

        // 3. Cookie fallback
        if (!$locale && $request->hasCookie('locale') && in_array($request->cookie('locale'), $supportedLocales, true)) {
            $locale = $request->cookie('locale');
        }

        // 4. Browser fallback
        if (!$locale) {
            $browser = strtolower(substr($request->getPreferredLanguage(), 0, 2));
            if (in_array($browser, $supportedLocales, true)) {
                $locale = $browser;
            }
        }

        // 5. Fallback to default
        $locale = $locale ?? config('app.locale', 'en');

        App::setLocale($locale);

        return $next($request);
    }
}
