<?php


namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class ImageService
{
    const MAX = 1800;

    public function make(UploadedFile $file)
    {
        $image = Image::make($file);

        [$width, $height] = $image->width() > $image->height() ? [self::MAX, null] : [null, self::MAX];

        return $image
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->stream('jpeg', 90);
    }

    public function makePreview(UploadedFile $file, $width = 200, $height = 200)
    {
        return Image::make($file)
            ->fit($width, $height, fn ($constraint) => $constraint->upsize())
            ->stream('jpeg', 90);
    }
}
