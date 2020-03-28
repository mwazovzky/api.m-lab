<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    protected $apiToken;

    public function __construct($resource, string $apiToken = null)
    {
        parent::__construct($resource);

        $this->apiToken = $apiToken;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'api_token' => $this->when($this->apiToken, $this->apiToken),
        ]);
    }
}
