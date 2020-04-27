<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Resources\{CategoryResource, CategoryResourceCollection};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $itemsPerPage = 20;

        $query = Category::query();

        return new CategoryResourceCollection($query->paginate($itemsPerPage));
    }

    public function tree()
    {
        $query = Category::whereNull('parent_id');

        return (new CategoryResourceCollection($query->get()))->withChildren();
    }

    public function show(Category $category, Request $request)
    {
        $attributes = $request->validate([
            'children' => 'sometimes|in:true,false',
            'siblings' => 'sometimes|in:true,false',
        ]);

        return (new CategoryResource($category))
            ->withChildren($attributes['children'] ?? false)
            ->withSiblings($attributes['siblings'] ?? false);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
            'parent_id' => 'sometimes|nullable|exists:categories,id',
        ]);

        $category = Category::create($attributes);

        return new CategoryResource($category);
    }

    public function update(Category $category, Request $request)
    {
        $attributes = $request->validate([
            'name' => 'sometimes|string',
            'parent_id' => 'sometimes|nullable|exists:categories,id',
        ]);

        $category->update($attributes);

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['status' => 'success', 200]);
    }
}
