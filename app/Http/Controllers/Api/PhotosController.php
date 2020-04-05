<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
use App\Http\Resources\PhotoResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    public function show(Photo $photo)
    {
        return new PhotoResource($photo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg', 'max:10000'],
        ]);

        $filename = uniqid() . '.jpg';
        Storage::disk('public')->putFileAs(Photo::UPLOAD_PATH, $request->file('image'), $filename);

        $photo = Photo::create([
            'filename' => $filename,
        ]);

        return new PhotoResource($photo);
    }

    public function update(Photo $photo, Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg', 'max:10000'],
        ]);

        Storage::disk('public')->delete(Photo::UPLOAD_PATH . '/' . $photo->filename);

        $filename = uniqid() . '.jpg';
        Storage::disk('public')->putFileAs(Photo::UPLOAD_PATH, $request->file('image'), $filename);

        $photo->update([
            'filename' => $filename,
        ]);

        return new PhotoResource($photo);
    }

    public function destroy(Photo $photo)
    {
        $photo->delete();

        return response()->json([
            'status' => 'success',
        ], 200);
    }
}
