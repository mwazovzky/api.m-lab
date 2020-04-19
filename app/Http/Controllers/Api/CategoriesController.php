<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $itemsPerPage = 20;

        $query = Category::query();

        return CategoryResource::collection($query->paginate($itemsPerPage));
    }
}
