<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    const FAVORITE_TYPES = [
        'posts' => Post::class,
    ];

    protected $fillable = ['favorite_type', 'favorite_id'];
}
