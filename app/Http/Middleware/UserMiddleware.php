<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->priority == 'LO'){
                return $next($request);
            } else {
                return redirect()->route('homedashboard'); //redirect in not user
            }
        }
    }
}