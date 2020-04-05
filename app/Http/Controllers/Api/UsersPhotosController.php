<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
use App\Http\Resources\PhotoResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsersPhotosController extends Controller
{
    public function show(User $user)
    {
        return new PhotoResource($user->photo);
    }

    public function store(User $user, Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg', 'max:10000'],
        ]);

        $filename = uniqid() . '.jpg';
        Storage::disk('public')->putFileAs(Photo::UPLOAD_PATH, $request->file('image'), $filename);

        $photo = $user->photos()->create([
            'filename' => $filename,
        ]);

        return new PhotoResource($photo);
    }

    public function destroy(User $user)
    {
        $user->photos()->delete();

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
