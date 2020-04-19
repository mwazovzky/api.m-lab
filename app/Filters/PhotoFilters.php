<?php

namespace App\Filters;

use App\Models\Photo;

class PhotoFilters extends Filters
{
    /**
     * List of available filters for Photo model.
     */
    public $modelFilters = ['entity_type', 'entity_id', 'is_primary'];

    protected function entity_type(string $type)
    {
        $this->builder->where('entity_type', Photo::ENTITIES[$type]);
    }

    protected function entity_id(int $id)
    {
        $this->builder->where('entity_id', $id);
    }

    protected function is_primary(bool $isPrimary)
    {
        $this->builder->whereIsPrimary($isPrimary);
    }
}
