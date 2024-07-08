<?php

namespace App\Utils;

use App\Models\TagCallback;
use App\Models\Tag;
use App\Models\Schematic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookUtils
{
    public static function sendWebhook(TagCallback $callback, Schematic $schematic, Tag $tag)
    {
        try {
            $payload = self::preparePayload($callback, $schematic, $tag);
            $headers = $callback->headers ?? [];

            Log::info('Sending webhook', ['url' => $callback->callback_url, 'payload' => $payload]);

            $response = Http::withHeaders($headers)->post($callback->callback_url, $payload);

            Log::info('Webhook response', ['status' => $response->status(), 'body' => $response->body()]);

            $callback->update(['last_triggered_at' => now()]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error sending webhook', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected static function preparePayload(TagCallback $callback, Schematic $schematic, Tag $tag)
    {
        switch ($callback->callback_format) {
            case 'discord':
                return self::prepareDiscordPayload($schematic, $tag);
            case 'json':
            default:
                return self::prepareJsonPayload($schematic, $tag);
        }
    }

    protected static function prepareDiscordPayload(Schematic $schematic, Tag $tag)
    {
        $eventType = $schematic->wasRecentlyCreated ? 'Created' : 'Updated';
        $mainAuthor = $schematic->authors->first();

        $authorInfo = $schematic->authors
            ->map(function ($player) {
                return "[{$player->lastSeenName}](https://minecraft-heads.com/profile/{$player->lastSeenName})";
            })
            ->implode(', ');

        $embed = [
            'title' => "Schematic {$eventType}: {$schematic->name}",
            'description' => "A new schematic has been {$eventType} with the tag '{$tag->name}'.",
            'color' => hexdec($tag->color ?? '7289DA'),
            'fields' => [
                [
                    'name' => 'Schematic ID',
                    'value' => $schematic->short_id,
                    'inline' => true,
                ],
                [
                    'name' => 'Tag',
                    'value' => $tag->name,
                    'inline' => true,
                ],
                [
                    'name' => 'Authors',
                    'value' => $authorInfo,
                    'inline' => false,
                ],
                [
                    'name' => 'Link',
                    'value' => '[View Schematic](' . url("/schematics/{$schematic->short_id}") . ')',
                    'inline' => false,
                ],
            ],
            'author' => [
                'name' => $mainAuthor ? $mainAuthor->lastSeenName : 'Unknown Author',
                'icon_url' => $mainAuthor ? "https://mc-heads.net/avatar/{$mainAuthor->lastSeenName}/100/nohelm" : null,
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        if ($schematic->preview_image) {
            $embed['image'] = ['url' => $schematic->preview_image];
        }

        $embed['thumbnail'] = [
            'url' => $mainAuthor ? "https://mc-heads.net/avatar/{$mainAuthor->lastSeenName}/100/nohelm" : 'https://via.placeholder.com/100x100?text=No+Author',
        ];

        return [
            'content' => "A new schematic has been {$eventType}!",
            'embeds' => [$embed],
            'attachments' => [],
        ];
    }

    protected static function prepareJsonPayload(Schematic $schematic, Tag $tag)
    {
        return [
            'event' => $schematic->wasRecentlyCreated ? 'schematic_created' : 'schematic_updated',
            'schematic' => [
                'id' => $schematic->short_id,
                'name' => $schematic->name,
                'authors' => $schematic->authors->map(function ($player) {
                    return [
                        'name' => $player->lastSeenName,
                        'head_url' => "https://mc-heads.net/avatar/{$player->lastSeenName}/100/nohelm",
                        'profile_url' => "https://minecraft-heads.com/profile/{$player->lastSeenName}",
                    ];
                }),
                'preview_image' => $schematic->preview_image,
                'preview_video' => $schematic->preview_video,
            ],
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'color' => $tag->color,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
