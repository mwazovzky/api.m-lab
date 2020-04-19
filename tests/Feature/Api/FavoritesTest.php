<?php

namespace Tests\Feature\Api;

use App\Models\Favorite;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_create_post_favorite()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['title' => 'Example title']);
        $this->assertCount(0, $user->favorites);

        $response = $this->actingAs($user, 'api')->json('POST', "/api/favorites/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertCount(1, $user->fresh()->favorites);
        tap($user->favorites()->first(), function ($favorite) use ($user, $post) {
            $this->assertEquals($user->id, $favorite->user_id);
            $this->assertEquals(Post::class, $favorite->favorite_type);
            $this->assertEquals($post->id, $favorite->favorite_id);
        });
    }

    /**
     * @test
     */
    public function user_can_delete_post_favorite()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['title' => 'Example title']);
        $user->favorites()->create([
            'favorite_type' => Post::class,
            'favorite_id' => $post->id,
        ]);
        $this->assertCount(1, $user->fresh()->favorites);

        $response = $this->actingAs($user, 'api')->json('DELETE', "/api/favorites/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertCount(0, $user->fresh()->favorites);
    }
}
