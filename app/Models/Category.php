<?php

namespace App\Models;

use App\AdjacencyList\AdjacencyList;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use AdjacencyList;

    protected $fillable = ['name', 'parent_id'];
}
