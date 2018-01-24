<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AppUserRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {


        //If request comes from logged in seller, he will
        //be redirected to seller's home page.
        if (Auth::guard('app_users')->check()) {
            return redirect('user-dashboard');
        }


        return $next($request);
    }
}
