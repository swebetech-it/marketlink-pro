<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via web guard and has ADMIN role
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && ($user->role === 'ADMIN' || session('admin_logged_in') === true)) {
                return $next($request);
            }
        }

        // Check fallback session key
        if (session('admin_logged_in') === true) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. SuperAdmin privileges required.'
            ], 401);
        }

        return redirect()->route('admin.login')->withErrors([
            'login' => 'Unahitaji kuingia kama Msimamizi Mkuu (SuperAdmin) ili kufikia jopo la usimamizi.'
        ]);
    }
}
