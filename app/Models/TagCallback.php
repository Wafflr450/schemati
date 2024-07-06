<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\WebhookUtils;

class TagCallback extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'callback_url', 'event_type', 'callback_format', 'created_by_user_id', 'is_active', 'last_triggered_at', 'headers', 'payload_template'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
        'headers' => 'json',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function trigger($schematic)
    {
        WebhookUtils::sendWebhook($this, $schematic, $this->tag);
    }
}
