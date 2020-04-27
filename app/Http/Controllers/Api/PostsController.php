<?php

namespace App\Http\Controllers\Api;

use App\Filters\PostFilters;
use App\Models\Post;
use App\Models\Tag;
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    public function index(Request $request, PostFilters $filters)
    {
        $request->validate([
            'search' => 'sometimes|string',
            'category' => 'sometimes|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'required|string',
            'favorite' => 'sometimes|in:true,false',
        ]);

        $limit = 20;

        $query = Post::query()
            ->filter($filters)
            ->orderBy('created_at', 'DESC');

        return PostResource::collection($query->paginate($limit));
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function store(PostRequest $request)
    {
        $attributes = $request->validated();
        /** @var User $user */
        $user = Auth::user();
        $tags = Tag::whereIn('name', $attributes['tags'])->get();
        $post = $user->posts()->create($attributes);
        $post->tags()->sync($tags);

        return new PostResource($post);
    }

    public function update(Post $post, PostRequest $request)
    {
        $this->authorize('update', $post);

        $attributes = $request->validated();

        $post->update($attributes);
        $tags = array_map(fn ($item) => $item['id'], $attributes['tags']);
        $post->tags()->sync($tags);

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('update', $post);

        $post->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
