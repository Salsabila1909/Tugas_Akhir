<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaMiddleware
{
    public function handle($request, Closure $next)
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->level != 0) {
        return redirect('/login');
    }

    return $next($request);
}
}