<?php

namespace App\Filters;

use App\Models\Category;
use Exception;

class PostFilters extends Filters
{
    /**
     * List of available filters for User model.
     */
    public $modelFilters = ['search', 'category', 'tags', 'orderby', 'favorite'];

    /**
     * Search posts bt query.
     */
    protected function search(string $search)
    {
        $q = "%{$search}%";

        $this->builder->where(function ($query) use ($q) {
            $query->where('title', 'LIKE', $q)
                ->orWhere('body', 'LIKE', $q);
        });
    }

    /**
     * Filter posts by category.
     */
    protected function category(string $name)
    {
        $this->builder->whereHas('category', function ($query) use ($name) {
            $category = Category::where('name', $name)->first();
            $query->whereIn('id', [$category->id, ...$category->siblings()->pluck('id')->toArray()]);
        });
    }

    /**
     * Filter posts by tags.
     */
    protected function tags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->builder->whereHas('tags', function ($query) use ($tag) {
                $query->where('name', $tag);
            });
        }
    }

    /**
     * Order posts by specified key.
     */
    protected function orderby(string $key)
    {
        $this->builder->orderBy($key, 'DESC');
    }

    /**
     * Filter favorite posts.
     */
    protected function favorite(string $value)
    {
        if ($value == 'false') {
            return;
        }

        $user = auth('api')->user();

        $this->builder->whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user ? $user->id : null);
        });
    }
}
