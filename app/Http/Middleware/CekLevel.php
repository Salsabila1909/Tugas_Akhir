<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CekLevel
{
    public function handle($request, Closure $next, ...$levels)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userLevel = Auth::user()->level;

        if (!empty($levels) && !in_array($userLevel, $levels)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}