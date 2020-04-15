<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request) + [
            'image' => $this->image,
            'preview' => $this->preview,
        ]);
    }
}
