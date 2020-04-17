<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    /**
     * Get post attached to the tag.
     *
     * @return Illuminate\Database\Eloquent\Relations\morphedByMany
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
