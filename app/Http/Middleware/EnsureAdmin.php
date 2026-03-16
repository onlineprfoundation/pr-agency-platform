<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Restrict admin routes to admin and member roles only.
     * Clients must use the portal.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || $user->isClient()) {
            abort(403, 'Admin access only.');
        }

        return $next($request);
    }
}
