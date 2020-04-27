<?php

use App\Trees\CategoryTree;
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
            [
                'name' => 'Software',
                'children' => [
                    [
                        'name' => 'Development',
                    ],
                    [
                        'name' => 'Testing',
                    ],
                    [
                        'name' => 'Devops',
                    ],
                ]
            ],
            [
                'name' => 'Travel',
                'children' => [
                    [
                        'name' => 'America',
                    ],
                    [
                        'name' => 'Europe',
                        'children' => [
                            [
                                'name' => 'Spain',
                            ],
                            [
                                'name' => 'France',
                            ],
                            [
                                'name' => 'Austria',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Personal',
            ],
        ];

        CategoryTree::create($categories);
    }
}
