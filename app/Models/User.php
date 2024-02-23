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

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['email', 'password', 'player_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['profile_photo_url'];

    //when a user is created, if there is no player associated with the user, create a player (player_id) create one
    public static function booted()
    {
        static::created(function ($user) {
            if (!$user->player) {
                $player = Player::create([
                    'id' => $user->player_id,
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
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function getNameAttribute()
    {
        return $this->player->last_seen_name ?? $this->player_id;
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->player->head_url;
    }
}
