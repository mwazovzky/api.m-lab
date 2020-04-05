<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    const UPLOAD_PATH = 'photos';

    protected $fillable = ['filename'];

    public function getUrlAttribute()
    {
        $host = config('app.url');

        return sprintf('%s/storage/%s/%s', $host, self::UPLOAD_PATH, $this->filename);
    }

    public function delete()
    {
        Storage::disk('public')->delete(Photo::UPLOAD_PATH . '/' . $this->filename);

        parent::delete();
    }
}
