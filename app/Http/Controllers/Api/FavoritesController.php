<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store(string $model, int $id)
    {
        auth()->user()->favorites()->create([
            'favorite_type' => Favorite::FAVORITE_TYPES[$model],
            'favorite_id' => $id,
        ]);

        return response(['status' => 'success'], 200);
    }

    public function destroy(string $model, int $id)
    {
        auth()->user()->favorites()->where([
            'favorite_type' => Favorite::FAVORITE_TYPES[$model],
            'favorite_id' => $id,
        ])->delete();

        return response(['status' => 'success'], 200);
    }
}
