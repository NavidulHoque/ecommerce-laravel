<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 400);
        }

        // Generate access token
        $accessTokenResult = $user->createToken('accessToken');
        $accessToken = $accessTokenResult->accessToken;
        $accessToken->expires_at = now()->addMinutes(4);
        $accessToken->save();
        $plainAccessToken = $accessTokenResult->plainTextToken;

        // Generate refresh token
        $refreshTokenResult = $user->createToken('refreshToken');
        $refreshToken = $refreshTokenResult->accessToken;
        $refreshToken->expires_at = now()->addDays(10);
        $refreshToken->save();
        $plainRefreshToken = $refreshTokenResult->plainTextToken;

        User::where('id', $user->id)->update(['refresh_token' => $plainRefreshToken]);

        return response([
            'user' => $user,
            'accessToken' => $plainAccessToken,
            "message" => 'Login successful'
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
        $user->refresh_token = null;
        $user->save();

        return response()->json(['message' => 'Logout successful'], 200);
    }

}

