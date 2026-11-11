<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotCustomer
{
    public function handle($request, Closure $next, $guard = 'customer')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('customer.login')->with('info', 'Please sign in to access your profile and order history.');
        }

        return $next($request);
    }
}