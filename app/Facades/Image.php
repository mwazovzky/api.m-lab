<?php

namespace App\Facades;

use App\Services\ImageService;
use Illuminate\Support\Facades\Facade;

class Image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ImageService::class;
    }
}
