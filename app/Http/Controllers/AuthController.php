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
            return response(['message' => 'Invalid credentials'], 401);
        }

        $accessToken = $user->createToken('apptoken')->plainTextToken;
        $refreshToken = $user->createToken('refreshToken')->plainTextToken;

        User::where('id', $user->id)->update(['refresh_token' => $refreshToken]);

        return response([
            'user' => $user,
            'token' => $accessToken,
            "message" => 'Login successful'
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(['message' => 'Logged out']);
    }
}

