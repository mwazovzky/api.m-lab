<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\PhoneResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersPhonesController extends Controller
{
    public function show(User $user)
    {
        return PhoneResource::collection($user->phones);
    }

    public function update(User $user, Request $request)
    {
        $attributes = $request->validate([
            'phones' => [
                'sometimes',
                'array'
            ],
            'phones.*.number' => [
                'required',
                'phone',
                Rule::unique('phones', 'number')->ignore($user->id, 'user_id'),
            ],
        ]);

        $user->phones()->delete();
        $user->phones()->createMany($attributes['phones']);

        return PhoneResource::collection($user->phones);
    }
}
