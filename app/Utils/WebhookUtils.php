<?php

namespace App\Utils;

use App\Models\TagCallback;
use App\Models\Tag;
use App\Models\Schematic;
use App\Models\Player;
use Illuminate\Support\Facades\Http;

class WebhookUtils
{
    public static function sendWebhook(TagCallback $callback, Schematic $schematic, Tag $tag)
    {
        $payload = self::preparePayload($callback, $schematic, $tag);
        $headers = $callback->headers ?? [];
        $response = Http::withHeaders($headers)->post($callback->callback_url, $payload);

        $callback->update(['last_triggered_at' => now()]);

        return $response->successful();
    }

    protected static function preparePayload(TagCallback $callback, Schematic $schematic, Tag $tag)
    {
        switch ($callback->callback_format) {
            case 'discord':
                $payload = self::prepareDiscordPayload($schematic, $tag);
                dd($payload);
                return $payload;
            case 'json':
            default:
                return self::prepareJsonPayload($schematic, $tag);
        }
    }

    protected static function prepareDiscordPayload(Schematic $schematic, Tag $tag)
    {
        $eventType = $schematic->wasRecentlyCreated ? 'Created' : 'Updated';
        $authors = $schematic->authors
            ->map(function ($author) {
                return [
                    'name' => $author->name,
                    'value' => "[Skin](https://mc-heads.net/avatar/{$author->name}/100/nohelm)",
                    'inline' => true,
                ];
            })
            ->toArray();

        return [
            'embeds' => [
                [
                    'title' => "Schematic {$eventType}: {$schematic->name}",
                    'description' => "A schematic has been {$eventType} with the tag '{$tag->name}'",
                    'color' => hexdec($tag->color ?? '7289DA'),
                    'fields' => array_merge(
                        [
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
                        ],
                        $authors,
                    ),
                    'image' => [
                        'url' => $schematic->preview_image ?? ($schematic->preview_video ?? 'https://via.placeholder.com/500x300?text=No+Preview'),
                    ],
                    'thumbnail' => [
                        'url' => "https://mc-heads.net/avatar/{$schematic->authors->first()->name}/100/nohelm",
                    ],
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ];
    }

    protected static function prepareJsonPayload(Schematic $schematic, Tag $tag)
    {
        return [
            'event' => $schematic->wasRecentlyCreated ? 'schematic_created' : 'schematic_updated',
            'schematic' => [
                'id' => $schematic->short_id,
                'name' => $schematic->name,
                'authors' => $schematic->authors->map(function ($author) {
                    return [
                        'name' => $author->name,
                        'skin_url' => "https://mc-heads.net/avatar/{$author->name}/100/nohelm",
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
