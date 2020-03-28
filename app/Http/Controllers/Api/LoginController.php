<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::where('email', $attributes['email'])->first();

        if ($user == null || Hash::check($attributes['password'], $user->password) == false) {
            return response()->json([
                'message' => 'Access denied.',
            ], 401);
        }

        $token = Str::random(80);

        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return response()->json([
            'api_token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->forceFill([
            'api_token' => null,
        ])->save();

        return response()->json([
            'message' => 'You have been logged out.'
        ], 200);
    }
}
