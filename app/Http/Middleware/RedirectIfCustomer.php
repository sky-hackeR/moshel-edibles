<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfCustomer
{
    public function handle($request, Closure $next, $guard = 'customer')
    {
        if (Auth::guard($guard)->check()) {
            return redirect()->intended('/');
        }

        return $next($request);
    }
}