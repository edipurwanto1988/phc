<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                abort(404);
            }

            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
