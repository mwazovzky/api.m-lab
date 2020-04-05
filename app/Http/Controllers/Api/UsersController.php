<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function profile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function index(Request $request)
    {
        $usersPerPage = 10;

        $query = User::query()->orderBy('created_at', 'DESC')->orderBy('name');

        return UserResource::collection($query->paginate($usersPerPage));
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
        ]);

        $user = User::create($attributes + ['password' => Hash::make('secret')]);

        return new UserResource($user);
    }

    public function update(User $user, Request $request)
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                Rule::in([$user->email]),
            ],
        ]);

        $user->update($attributes);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return new UserResource($user);
    }
}
