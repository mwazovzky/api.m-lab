<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $apiToken;

    public function __construct($resource, $apiToken = null)
    {
        parent::__construct($resource);

        $this->apiToken = $apiToken;
    }

    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'role' => new RoleResource($this->role),
            'phones' => PhoneResource::collection($this->phones),
            'photo' => new PhotoResource($this->photo),
            'api_token' => $this->when($this->apiToken, $this->apiToken),
        ]);
    }
}
