<?php

namespace App;

use App\Models\Tag;

trait Taggable
{
    /**
     * Detach all tags (and clean pivot table) when deleting the model.
     */
    protected static function bootTaggable()
    {
        static::deleted(function ($model) {
            $model->tags()->detach();
        });
    }

    /**
     * Get tags attached to the post.
     *
     * @return Illuminate\Database\Eloquent\Relations\morphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
