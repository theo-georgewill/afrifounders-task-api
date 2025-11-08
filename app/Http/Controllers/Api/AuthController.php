<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // REGISTER
    public function register(RegisterRequest $request)
    {
        // The RegisterRequest handles validation automatically
        $data = $request->validated();

        // Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);


        // Login to get session (for session-based auth)
        Auth::login($user);

        // Create API/mobile token (for token-based auth)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // LOGIN
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Attempt login
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Important: regenerate session!
        $request->session()->regenerate();


       // If frontend requests token-based login (for API/mobile use)
        if ($request->boolean('with_token')) {
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful (token-based)',
                'user' => $user,
                'token' => $token,
                'auth_type' => 'token',
            ]);
        }

        // Default: session-based login (cookie mode)
        return response()->json([
            'message' => 'Login successful (cookie-based)',
            'user' => $user,
            'auth_type' => 'cookie',
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        // If using token-based auth
        if ($request->user()?->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out (token-based)']);
        }

        // Otherwise, destroy session (cookie-based)
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out (cookie-based)']);
    }

    //get current logged in user data
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
