<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Player;

use App\Utils\CommonUtils;

use Illuminate\Support\Facades\Storage;
use App\Observers\SchematicObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([SchematicObserver::class])]
class Schematic extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = ['name', 'description', 'id', 'is_public'];

    protected $casts = [
        'id' => 'string',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($schematic) {
            if (empty($schematic->id)) {
                $schematic->id = Str::uuid();
            }
            if (empty($schematic->short_id)) {
                $schematic->short_id = self::generateUniqueShortId($schematic->id);
            }
            if (empty($schematic->slug)) {
                $schematic->slug = self::generateUniqueSlug($schematic->name);
            }
        });

        static::saving(function ($schematic) {
            if ($schematic->isDirty('name') && empty($schematic->slug)) {
                $schematic->slug = self::generateUniqueSlug($schematic->name);
            }
        });

        static::deleting(function ($schematic) {
            info('deleting');
            $schematic->clearMediaCollection('schematic');
            $schematic->clearMediaCollection('preview_video');
            $schematic->clearMediaCollection('preview_image');
            info('deleted');
        });
    }

    protected static function generateUniqueShortId($input, $length = 6)
    {
        $shortId = CommonUtils::smallHash($input, $length);
        while (self::where('short_id', $shortId)->exists()) {
            $length++;
            $shortId = CommonUtils::smallHash($input, $length);
        }
        return $shortId;
    }

    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = 2;
        while (self::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . $count;
            $count++;
        }
        return $slug;
    }

    public function getDownloadLinkAttribute()
    {
        $link = $this->getFirstMediaUrl('schematic');
        return str_replace('minio', 'localhost', $link);
    }

    public function getFileAttribute()
    {
        $media = $this->getFirstMedia('schematic');
        //dump the url
        if (!$media) {
            return null;
        }
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

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getRouteKeyName()
    {
        return 'short_id';
    }
}
