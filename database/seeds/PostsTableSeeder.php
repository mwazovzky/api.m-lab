<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($index = 0; $index  < 100; $index++) {
            $post = factory(Post::class)->create([
                'category_id' => Category::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
            ]);

            $count = rand(0, 5);
            $tags = Tag::inRandomOrder()->limit($count)->get();
            $post->tags()->sync($tags);
        }
    }
}
