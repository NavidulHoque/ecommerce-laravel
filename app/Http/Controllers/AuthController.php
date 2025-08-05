<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => $this->baseValidation(),
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password']
        ]);

        return response([
            'data' => $user,
            "message" => 'User registered successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => $this->baseValidation()
        ]);

        $user = $this->findUser("email", $fields['email']);

        if (!$user) {
            return response(['message' => 'Invalid email'], 400);
        }

        if (!Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid password'], 400);
        }

        $accessToken = $this->generateAccessToken($user);

        $refreshToken = $this->generateRefreshToken($user);

        User::where('id', $user->id)->update(['refresh_token' => $refreshToken]);

        return response([
            'user' => $user,
            'accessToken' => $accessToken,
            "message" => 'Login successful'
        ], 200);
    }

    public function logout()
    {
        $user = auth('jwt')->user();

        // Invalidate access token
        JWTAuth::invalidate(JWTAuth::getToken());

        // Clear stored refresh token
        if ($user instanceof \App\Models\User) {
            $user->refresh_token = null;
            $user->save();
        }

        return response()->json(['message' => 'Logout successful'], 200);
    }

    public function refreshToken(Request $request)
    {
        $fields = $request->validate(rules: [
            'refresh_token' => $this->baseValidation()
        ]);

        $user = $this->findUser("refresh_token", $fields['refresh_token']);

        if (!$user) {
            return response(['message' => 'Invalid refresh token'], 400);
        }

        try {
            // Set the token to validate
            JWTAuth::setToken($fields['refresh_token']);

            // Decode the token and get the payload
            $payload = JWTAuth::getPayload();

            // Ensure it's a refresh token
            if (!$payload->get('refresh')) {
                return response()->json(['message' => 'Token is not a refresh token'], 401);
            }

            // Generate new access token
            $accessToken = $this->generateAccessToken($user);

            // Generate new refresh token
            $refreshToken = $this->generateRefreshToken($user);

            // Update user's refresh token
            $user->refresh_token = $refreshToken;
            $user->save();

            return response()->json([
                'accessToken' => $accessToken,
                'message' => 'Token refreshed successfully'
            ], 200);

        }

        catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Refresh token has expired'], 401);
        }

        catch (JWTException $e) {
            return response()->json(['message' => 'Refresh token is missing or not provided'], 400);
        }
    }

    protected function generateAccessToken(User $user)
    {
        return JWTAuth::claims([
            'exp' => now()->addDays(7)->timestamp
        ])->fromUser($user);
    }

    protected function generateRefreshToken(User $user)
    {
        return JWTAuth::claims([
            'refresh' => true,
            'exp' => now()->addDays(10)->timestamp
        ])->fromUser($user);
    }
}
