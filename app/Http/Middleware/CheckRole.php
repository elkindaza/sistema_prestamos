<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user || !$user->role || $user->role->nombre !== $role) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
