<?php

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = ['PHP', 'Laravel', 'Vue', 'TDD', 'Devops'];

        foreach ($tags as $tag) {
            factory(Tag::class)->create(['name' => $tag]);
        }
    }
}
