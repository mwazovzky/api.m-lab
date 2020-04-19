<?php

namespace App\Models;

use App\Taggable;
use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use Taggable, Filterable;

    protected $fillable = ['title', 'body', 'category_id'];

    /**
     * Hook to model:created event to make a post slug.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->slug = $post->title;
        });
    }

    /**
     * Set unique post slug attribute.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute(string $value)
    {
        $slug = Str::slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'entity');
    }

    public function getPhotoAttribute()
    {
        return $this->photos()->where('is_primary', true)->first();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorite');
    }

    public function isFavorite(User $user = null): bool
    {
        return $this->favorites()->where('user_id', $user ? $user->id : null)->exists();
    }
}
