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
    }

    public function getDownloadLinkAttribute()
    {
        $link = $this->getFirstMediaUrl('schematics');
        return str_replace('minio', 'localhost', $link);
    }

    public function getFileAttribute()
    {
        $media = $this->getFirstMedia('schematic');
        $disk = $media->disk;
        $fileName = $media->file_name;
        $model_id = $media->model_id;
        return Storage::disk($disk)->get($model_id . '/' . $fileName);
    }

    public function getBase64Attribute()
    {
        $file = $this->file;
        $b64 = base64_encode($file);
        return $b64;
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
