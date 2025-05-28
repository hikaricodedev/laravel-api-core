<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',

        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('user-token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|unique:users,email',
                'password' => 'required|confirmed',
                'name' => 'required'
            ]);

            User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name
            ]);

            return response()->json(['user' => [
                'email' => $request->email,
                'name' => $request->name
            ]]);
        } catch (\Throwable $e){
            return response()->json(['message' => $e],500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
