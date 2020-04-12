<?php

namespace App\Filters;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     *  Apply filters to the model query.
     */
    public function scopeFilter(Builder $query, Filters $filters)
    {
        return $filters->apply($query);
    }
}
