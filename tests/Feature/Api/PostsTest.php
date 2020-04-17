<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_sync_post_tags()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();
        $tags = [
            factory(Tag::class)->create(['name' => 'PHP'])->toArray(),
            factory(Tag::class)->create(['name' => 'Laravel'])->toArray(),
            factory(Tag::class)->create(['name' => 'Vue'])->toArray(),
        ];

        $this->assertCount(0, $post->tags);

        $response = $this->actingAs($user, 'api')->json('PUT', "/api/posts/{$post->id}", [
            'title' => $post->title,
            'body' => $post->body,
            'category_id' => null,
            'tags' => $tags,
        ]);

        $response->assertStatus(200);
        tap($post->fresh()->tags, function ($tags) {
            $this->assertCount(3, $tags);
            $this->assertEquals(['Laravel', 'PHP', 'Vue'], $tags->sortBy('name')->pluck('name')->toArray());
        });
    }
}
