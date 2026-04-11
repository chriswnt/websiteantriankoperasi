<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Kalau belum login → lempar ke login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Kalau role tidak sesuai → forbidden
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}