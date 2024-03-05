<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Player;

use Illuminate\Support\Facades\Storage;

class Schematic extends Model implements HasMedia
{
    use InteractsWithMedia;
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
                dd('creating schematic');
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

    public function getFileAttribute()
    {
        return Storage::disk('schematics')->get('schematics/' . $this->id . '.schem');
    }

    public function getBase64Attribute()
    {
        return base64_encode($this->file);
    }

    public function authors()
    {
        return $this->belongsToMany(Player::class, 'players_schematics', 'schematic_id', 'player_id');
    }

    public function getStringIdAttribute()
    {
        return str_replace('-', '', $this->id);
    }

    public function getPreviewVideoAttribute()
    {
        $firstMedia = $this->getFirstMediaUrl('preview_video');
        if (!$firstMedia) {
            return null;
        }
        return str_replace('minio', 'localhost', $firstMedia);
    }

    public function getPreviewImageAttribute()
    {
        $firstMedia = $this->getFirstMediaUrl('preview_image');
        if (!$firstMedia) {
            return null;
        }
        return str_replace('minio', 'localhost', $firstMedia);
    }
}
