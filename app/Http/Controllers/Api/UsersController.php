<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
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

    public function store(UserRequest $request)
    {
        $attributes = $request->validated();

        $user = User::create($attributes + ['password' => Hash::make('secret')]);

        return new UserResource($user);
    }

    public function update(User $user, UserRequest $request)
    {
        $attributes = $request->validated();

        $user->update($attributes);

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return new UserResource($user);
    }
}
