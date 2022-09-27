<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUserDisabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = $request->user();

        if ($user && $user->is_disabled) {
            $user->currentAccessToken()->delete();
            return response()->json(['error' => 'User disabled.'], 403);
        }

        return $next($request);
    }
}
