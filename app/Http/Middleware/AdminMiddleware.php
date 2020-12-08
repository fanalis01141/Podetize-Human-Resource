<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->priority == 'HI'){
                return $next($request);
            } else {
                return redirect()->route('home'); //redirect in not admin
            }
        }
    }
}