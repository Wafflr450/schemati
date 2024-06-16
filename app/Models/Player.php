<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Utils\MinecraftAPI;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'last_seen_name'];

    protected $casts = [
        'id' => 'string',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    protected $appends = ['head_url']; // Add this line

    public static function booted()
    {
        static::creating(function ($player) {
            if (!Arr::has($player, 'last_seen_name')) {
                $player->last_seen_name = MinecraftAPI::getUsername($player->id);
            } elseif (!Arr::has($player, 'id')) {
                $player->id = MinecraftAPI::getUUID($player->last_seen_name);
            }
        });
    }

    public function getHeadUrlAttribute(): string
    {
        return MinecraftAPI::getHeadImageURL($this->id);
    }

    public function getLastSeenNameAttribute(): string
    {
        $last_seen_name = $this->attributes['last_seen_name'] ?? null;
        if (!$last_seen_name) {
            $last_seen_name = MinecraftAPI::getUsername($this->id);
            $this->last_seen_name = $last_seen_name;
            $this->save();
        }
        return $last_seen_name;
    }

    public function schematics()
    {
        return $this->belongsToMany(Schematic::class, 'users_schematics');
    }

    public function getTopMostAdminTagsAttribute()
    {
        return Tag::getTopMostAdminedTags($this);
    }

    public function getUsableTagsAttribute()
    {
        return Tag::getUsableTags($this);
    }

    public function getVisibleTagsAttribute()
    {
        return Tag::getVisibleTags($this);
    }
}
