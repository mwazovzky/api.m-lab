<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Filters
{
    /**
     * List of available filters for the model.
     */
    protected $modelFilters = [];
    protected $filters;
    protected $builder;

    /**
     * Create new Filters instance.
     */
    public function __construct(array $filters = null, Request $request = null)
    {
        if ($filters == null && $request != null) {
            $filters = $request->all();
        }

        $this->filters = $filters;
    }

    /**
     * Apply filters.
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Get a list of applicable filters.
     */
    protected function getFilters(): array
    {
        return array_intersect_key($this->filters, array_flip($this->modelFilters));
    }
}
