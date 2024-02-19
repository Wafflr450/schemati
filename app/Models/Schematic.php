<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class Schematic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'id'];

    protected $casts = [
        'id' => 'string',
    ];

    protected $keyType = 'string';

    public static function booted()
    {
        static::creating(function ($schematic) {
            if (empty($schematic->id)) {
                $schematic->id = Str::uuid();
            }
        });

        static::deleting(function ($schematic) {
            Storage::disk('schematics')->delete('schematics/' . $schematic->id . '.schem');
        });
    }

    public function getDownloadLinkAttribute()
    {
        $link = Storage::disk('schematics')->url('schematics/' . $this->id . '.schem');
        //if the link is to minio we are inside the docker container and need to replace it with localhost
        if (Str::contains($link, 'minio')) {
            $link = str_replace('minio', 'localhost', $link);
        }
        return $link;
    }
}
