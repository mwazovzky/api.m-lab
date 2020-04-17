<?php

namespace App\Models;

use App\Facades\Image;
use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use Filterable;

    const IMAGE_PATH = 'images';
    const PREVIEW_PATH = 'preview';

    const ENTITIES = [
        'users' => User::class,
        'posts' => Post::class,
    ];

    protected $fillable = ['filename', 'entity_type', 'entity_id', 'is_primary'];

    public function getImageAttribute()
    {
        $host = config('app.url');

        return sprintf('%s/storage/%s/%s', $host, self::IMAGE_PATH, $this->filename);
    }

    public function getPreviewAttribute()
    {
        $host = config('app.url');

        return sprintf('%s/storage/%s/%s', $host, self::PREVIEW_PATH, $this->filename);
    }

    public static function create(array $attributes = [], UploadedFile $file): self
    {
        $filename = uniqid() . '.jpg';

        $image = Image::make($file);
        Storage::disk('public')->put(Photo::IMAGE_PATH . '/' . $filename, $image);

        $preview = Image::makePreview($file);
        Storage::disk('public')->put(Photo::PREVIEW_PATH . '/' . $filename, $preview);

        return static::query()->create([
            'entity_type' => self::ENTITIES[$attributes['entity_type']],
            'entity_id' => $attributes['entity_id'],
            'is_primary' => $attributes['is_primary'] ?? null,
            'filename' => $filename,
        ]);
    }

    public function update(array $attributes = [], array $options = [])
    {
        $file = $options['image'];
        $filename = uniqid() . '.jpg';

        Storage::disk('public')->delete(self::IMAGE_PATH . '/' . $this->filename);
        $image = Image::make($file);
        Storage::disk('public')->put(Photo::IMAGE_PATH . '/' . $filename, $image);

        Storage::disk('public')->delete(self::PREVIEW_PATH . '/' . $this->filename);
        $preview = Image::makePreview($file);
        Storage::disk('public')->put(Photo::PREVIEW_PATH . '/' . $filename, $preview);

        return parent::update([
            'filename' => $filename,
        ]);
    }

    public function delete()
    {
        Storage::disk('public')->delete(Photo::IMAGE_PATH . '/' . $this->filename);

        parent::delete();
    }
}
