<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->account_type == '0' || Auth::user()->account_type == '1' || Auth::user()->account_type == '2') {
                return $next($request);
            } else {
                return redirect('/')->with('error', 'Access Denied!');
            }
        } else {
            return redirect('/')->with('error', 'Please login first!');
        }
    }
}
