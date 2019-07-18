<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthAdmin
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
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->role == User::ROLE_SUPERADMIN) {
                if (Auth::user()->role != User::ROLE_SUPERADMIN) {
                    return redirect('/home');
                }
            }elseif(Auth::user()->role == User::ROLE_ADMIN){
                if (Auth::user()->role != User::ROLE_ADMIN) {
                    return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}
