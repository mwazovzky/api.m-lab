<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\EmailConfirmationEmail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Exception;

class ResetPasswordController extends Controller
{
    public function sendCode(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'string', 'exists:users'],
        ]);

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

    public function resetPassword(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required'],
            'code' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (Redis::get('register-code' . $attributes['email']) !== $attributes['code']) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['code' => 'Invalid code']
            ], 422);
        }

        $user = User::where('email', $attributes['email'])->first();

        $user->update([
            'password' => Hash::make($attributes['password']),
        ]);

        return response()->json([
            'message' => 'Your email has been updated.',
        ], 200);
    }
}
