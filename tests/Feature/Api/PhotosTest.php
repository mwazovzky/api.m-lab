<?php

namespace Tests\Feature\Api;

use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotosTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_store_photo()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $response = $this->actingAs($user, 'api')->json('POST', '/api/photos', [
            'image' => $file,
            'entity_type' => 'posts',
            'entity_id' => $post->id,
            'is_primary' => true,
        ]);

        $response->assertStatus(201);
        tap($post->fresh(), function ($post) {
            $this->assertCount(1, $post->photos);
            Storage::disk('public')->assertExists(Photo::IMAGE_PATH . '/' . $post->photos()->first()->filename);
        });
    }

    /**
     * @test
     */
    public function it_can_update_photo()
    {
        $user = factory(User::class)->create();
        $filename = 'image.jpg';
        $photo = factory(Photo::class)->create(['filename' => $filename]);
        Storage::fake('public');
        Storage::disk('public')->putFileAs(Photo::IMAGE_PATH, UploadedFile::fake()->image('image.jpg'), $filename);
        Storage::disk('public')->assertExists(Photo::IMAGE_PATH . '/' . 'image.jpg');
        $this->assertEquals('image.jpg', $photo->filename);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/photos/' . $photo->id, [
            'image' => UploadedFile::fake()->image('updated-image.jpg'),
        ]);

        $response->assertStatus(200);

        tap($photo->fresh()->filename, function ($filename) {
            Storage::disk('public')->assertMissing(Photo::IMAGE_PATH . '/' . 'image.jpg');
            $this->assertNotEquals('image.jpg', $filename);
            Storage::disk('public')->assertExists(Photo::IMAGE_PATH . '/' . $filename);
        });
    }

    /**
     * @test
     */
    public function it_can_destroy_photo()
    {
        $user = factory(User::class)->create();
        $filename = 'fake-image.jpg';
        $photo = factory(Photo::class)->create(['filename' => $filename]);
        Storage::fake('public');
        Storage::disk('public')->putFileAs(Photo::IMAGE_PATH, UploadedFile::fake()->image('image.jpg'), $filename);
        Storage::disk('public')->assertExists(Photo::IMAGE_PATH . '/' . $filename);

        $response = $this->actingAs($user, 'api')->json('DELETE', '/api/photos/' . $photo->id);

        $response->assertStatus(200);
        Storage::disk('public')->assertMissing(Photo::IMAGE_PATH . '/' . $filename);
    }
}
