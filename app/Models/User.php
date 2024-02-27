<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Utils\MinecraftAPI;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    public $fillable = ['uuid'];

    public static function booted()
    {
        static::created(function ($user) {
            if (!$user->player) {
                $player = Player::create([
                    'id' => $user->uuid,
                ]);
            }
        });
    }

    public function schematics()
    {
        return $this->belongsToMany(Schematic::class, 'users_schematics');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'uuid', 'id');
    }

    public function getProfilePhotoUrlAttribute()
    {
        return MinecraftAPI::getHeadImageURL($this->uuid);
    }

    public function getNameAttribute()
    {
        return $this->player->last_seen_name ?? $this->uuid;
    }
}
