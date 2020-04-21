<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
        return array_merge(parent::toArray($request) + [
            'children' => $this->when(
                $this->appendChildren,
                CategoryResourceCollection::make($this->children())->withChildren()
            ),
            'siblings' => $this->when(
                $this->appendSiblings,
                CategoryResourceCollection::make($this->siblings())
            ),
        ]);
    }
}
