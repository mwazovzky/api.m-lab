<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN = 'admin';

    protected $fillable = ['name', 'description'];
}
