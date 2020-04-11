<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhoneResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersPhonesController extends Controller
{
    public function show(User $user)
    {
        return PhoneResource::collection($user->phones);
    }

    public function update(User $user, Request $request)
    {
        $attributes = $request->validate([
            'phones' => ['sometimes', 'array'],
            'phones.*.number' => ['required', 'string'],
        ]);


        $user->phones()->delete();
        $user->phones()->createMany($attributes['phones']);

        return PhoneResource::collection($user->phones);
    }
}
