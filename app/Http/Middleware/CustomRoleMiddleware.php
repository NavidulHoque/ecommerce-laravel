<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\RoleMiddleware as SpatieRoleMiddleware;

class CustomRoleMiddleware extends SpatieRoleMiddleware
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        $user = $request->user();

        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }
        
        $roles = is_array($role) ? $role : explode('|', $role);
        foreach ($roles as $singleRole) {
            if ($user->hasRole($singleRole, $guard)) {
                return $next($request);
            }
        }

        // 🚫 Custom error message here
        return response()->json([
            'message' => 'Your role is not authorized to perform this action.',
            'required_roles' => $roles,
            'your_role' => $user->getRoleNames(),
        ], 403);
    }
}
