<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $date = $faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now');

    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph(100),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});
