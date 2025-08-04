<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request has an authorization header
        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authorization header missing'
            ], 401);
        }

        // Extract the token from the header
        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        // Check if token exists and is valid
        if (empty($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token not provided'
            ], 401);
        }

        return $next($request);
    }

    protected function isValidToken(string $token): bool
    {
        // This is a placeholder - implement your actual token validation
        // For JWT tokens, you might check the structure, signature, etc.
        return !empty($token) && strlen($token) > 30;
    }
}
