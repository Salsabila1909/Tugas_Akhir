<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
 public function handle($request, Closure $next)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->level != 1) {
        abort(403);
    }

    return $next($request);
}
}
