<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Super Admin always has full access
        if ($user->role && $user->role->name === 'Super Admin') {
            return $next($request);
        }

        if ($user->role && in_array($user->role->name, $roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk halaman ini.');
    }
}
