<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Tag;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $itemsPerPage = 20;

        $query = Post::query()->orderBy('created_at', 'DESC');

        return PostResource::collection($query->paginate($itemsPerPage));
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function store(PostRequest $request)
    {
        $attributes = $request->validated();

        $post = Post::create($attributes);
        $tags = Tag::whereIn('name', $attributes['tags'])->get();
        $post->tags()->sync($tags);

        return new PostResource($post);
    }

    public function update(Post $post, PostRequest $request)
    {
        $attributes = $request->validated();

        $post->update($attributes);
        $tags = array_map(fn ($item) => $item['id'], $attributes['tags']);
        $post->tags()->sync($tags);

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
