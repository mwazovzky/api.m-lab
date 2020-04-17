<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index()
    {
        $query = Tag::query()->orderBy('name')->withCount('posts');

        return TagResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => ['string', 'unique:tags'],
        ]);

        $tag = Tag::create($attributes);

        return new TagResource($tag);
    }

    public function update(Tag $tag, Request $request)
    {
        $attributes = $request->validate([
            'name' => [
                'string',
                Rule::unique('tags')->ignore($tag),
            ],
        ]);

        $tag->update($attributes);

        return new TagResource($tag);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
