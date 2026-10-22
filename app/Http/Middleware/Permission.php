<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Lolos jika user memiliki SALAH SATU permission yang diberikan.
     * Contoh: ->middleware('permission:manage_orders,edit_orders')
     */
    public function handle(Request $request, Closure $next, string ...$permissions)
    {
        if (!Auth::check()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                abort(404);
            }

            return redirect()->route('login');
        }

        $user = Auth::user();

        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return abort(403, 'Unauthorized');
    }
}
