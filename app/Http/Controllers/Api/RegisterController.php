<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Mail\EmailConfirmationEmail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Exception;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Redis::set('register-user' . $attributes['email'], json_encode($attributes), 'EX', 60 * 5);

        try {
            $code = 11111; // mt_rand(10000, 99999);
            Redis::set('register-code' . $attributes['email'], $code, 'EX', 60 * 5);
            Mail::to($attributes['email'])->send(new EmailConfirmationEmail($code));
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => ['email' => 'Failed to send verification code.']
            ], 422);
        }

        return response()->json([
            'message' => 'Email verification code has been sent to your email. Code we be invalidated in 5 minutes.',
        ], 200);
    }

    public function verifyEmail(Request $request)
    {
        $params = $request->validate([
            'email' => ['required'],
            'code' => ['required'],
        ]);

        if (Redis::get('register-code' . $params['email']) !== $params['code']) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['code' => 'Invalid code']
            ], 422);
        }

        $attributes = json_decode(Redis::get('register-user' . $params['email']), true);

        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);

        return new UserResource($user);
    }
}
