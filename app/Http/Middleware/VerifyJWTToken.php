<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $request["user"] = $user;
        }

        catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired'], 401);
        }

        catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Token is invalid'], 401);
        }

        catch (JWTException $e) {
            return response()->json(['message' => 'Token is missing or not provided'], 401);
        }

        return $next($request);
    }
}
