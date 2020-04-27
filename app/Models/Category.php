<?php

namespace App\Models;

use App\Relations\ChildrenRelation;
use App\Relations\SiblingsRelation;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function children(): ChildrenRelation
    {
        return new ChildrenRelation($this);
    }

    public function siblings(): SiblingsRelation
    {
        return new SiblingsRelation($this);
    }
}
