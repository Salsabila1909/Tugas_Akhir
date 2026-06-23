<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CekLevel
{
   public function handle($request, Closure $next, ...$levels)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    // pastikan level benar-benar integer
    $userLevel = (int) $user->level;

    $allowedLevels = array_map('intval', $levels);

    if (!in_array($userLevel, $allowedLevels)) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
}