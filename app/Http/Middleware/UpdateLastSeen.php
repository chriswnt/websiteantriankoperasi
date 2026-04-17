<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'officer') {
            auth()->user()->forceFill([
                'last_seen' => now(),
            ])->save();
        }

        return $next($request);
    }
}