<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        
        if (! $user) {
            Log::warning('Unauthenticated user attempted to access protected route', [
                'path' => $request->path(),
                'ip' => $request->ip()
            ]);
            abort(401, 'Unauthenticated.');
        }

        $userRole = $user->role;
        
        if (! in_array($userRole, $roles, true)) {
            Log::warning('User attempted to access unauthorized route', [
                'user_id' => $user->id,
                'user_role' => $userRole,
                'required_roles' => $roles,
                'path' => $request->path()
            ]);
            abort(403, 'Unauthorized action.');
        }
        
        Log::info('User accessed protected route', [
            'user_id' => $user->id,
            'user_role' => $userRole,
            'path' => $request->path()
        ]);

        return $next($request);
    }
}


