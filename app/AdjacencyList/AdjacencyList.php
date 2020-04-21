<?php

namespace App\AdjacencyList;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait AdjacencyList
{
    public function children(): Collection
    {
        return self::query()->where('parent_id', $this->id)->get();
    }

    public function siblings(): Collection
    {
        $queryBuilder = (new AdjacencyListBuilder($this->getTable(), $this->id))->get();
        $builder = new Builder($queryBuilder);
        /** @var Model $this */
        $builder->setModel($this);
        return $builder->get();
    }
}
