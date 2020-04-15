<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
use App\Filters\PhotoFilters;
use App\Http\Resources\PhotoResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhotosController extends Controller
{
    public function index(PhotoFilters $filters)
    {
        $query = Photo::query()
            ->filter($filters)
            ->orderBy('created_at', 'DESC');

        return PhotoResource::collection($query->get());
    }

    public function show(Photo $photo)
    {
        return new PhotoResource($photo);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg,bmp,png', 'max:10000'],
            'entity_type' => ['sometimes', 'string', Rule::in(array_keys(Photo::ENTITIES))],
            'entity_id' => ['sometimes', 'int', Rule::exists($request->entity_type, 'id')],
            'is_primary' => ['sometimes', 'bool'],
        ]);

        $photo = Photo::create($attributes, $request->file('image'));

        return new PhotoResource($photo);
    }

    public function update(Photo $photo, Request $request)
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpeg,bmp,png', 'max:10000'],
        ]);

        $photo->update([], [
            'image' => $request->file('image'),
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
