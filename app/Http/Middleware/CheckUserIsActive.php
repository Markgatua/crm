<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckUserIsActive
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Use the web guard for logout
        if (Auth::guard('web')->check() && Auth::user()->is_active == false) {
            Auth::guard('web')->logout(); // Ensure you're logging out using the correct guard
            return redirect()->route('login')->withErrors(['Your account is inactive. Please contact support.']);
        }

        return $next($request);
    }
}
