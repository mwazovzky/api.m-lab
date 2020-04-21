<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
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
        return $this->hasMany(Favorite::class);
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === Role::ADMIN;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
