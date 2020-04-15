<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use Filterable;

    const UPLOAD_PATH = 'images';

    const ENTITIES = [
        'users' => User::class,
        'posts' => Post::class,
    ];


    protected $fillable = ['filename', 'entity_type', 'entity_id', 'is_primary'];

    public function getUrlAttribute()
    {
        $host = config('app.url');

        return sprintf('%s/storage/%s/%s', $host, self::UPLOAD_PATH, $this->filename);
    }

    public static function create(array $attributes = [], UploadedFile $file): self
    {
        $filename = uniqid() . '.jpg';
        Storage::disk('public')->putFileAs(self::UPLOAD_PATH, $file, $filename);

        return static::query()->create([
            'entity_type' => self::ENTITIES[$attributes['entity_type']],
            'entity_id' => $attributes['entity_id'],
            'is_primary' => $attributes['is_primary'] ?? null,
            'filename' => $filename,
        ]);
    }

    public function update(array $attributes = [], array $options = [])
    {
        Storage::disk('public')->delete(self::UPLOAD_PATH . '/' . $this->filename);

        $filename = uniqid() . '.jpg';
        Storage::disk('public')->putFileAs(self::UPLOAD_PATH, $options['image'], $filename);

        return parent::update([
            'filename' => $filename,
        ]);
    }

    public function delete()
    {
        Storage::disk('public')->delete(Photo::UPLOAD_PATH . '/' . $this->filename);

        parent::delete();
    }
}
