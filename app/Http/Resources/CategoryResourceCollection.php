<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryResourceCollection extends ResourceCollection
{
    protected bool $appendChildren = false;
    protected bool $appendSiblings = false;

    public function withChildren(bool $value = true)
    {
        $this->appendChildren = $value;

        return $this;
    }

    public function withSiblings(bool $value = true)
    {
        $this->appendSiblings = $value;

        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(function (CategoryResource $resource) use ($request) {
            return $resource
                ->withChildren($this->appendChildren)
                ->withSiblings($this->appendSiblings)
                ->toArray($request);
        })->all();
    }
}
