<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'photo' => new PhotoResource($this->photo),
            'phones' => PhoneResource::collection($this->phones),
        ]);
    }
}
