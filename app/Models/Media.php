<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Media extends BaseMedia
{
    protected $keyType = 'string';

    public $incrementing = false;

    public function getKey()
    {
        $model = $this->model_type::find($this->model_id);
        return (string) $model->id;
    }

    protected function originalUrl(): Attribute
    {
        return Attribute::get(fn() => $this->getUrl());
    }

    public function url(): Attribute
    {
        $url = parent::getUrl();
        if (Str::contains($url, 'minio')) {
            $url = str_replace('minio', 'localhost', $url);
        }
        return Attribute::get(fn() => $url);
    }
}
