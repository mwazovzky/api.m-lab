<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Development'],
            ['name' => 'Testing'],
            ['name' => 'Travel'],
            ['name' => 'Personal'],
        ];

        foreach ($categories as $category) {
            factory(Category::class)->create($category);
        }
    }
}
