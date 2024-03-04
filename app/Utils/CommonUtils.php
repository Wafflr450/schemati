<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class CommonUtils
{
    public static function isUUID(string $uuid): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid);
    }

    public static function smallHash(string $input): string
    {
        return substr(md5($input), 0, 8);
    }

    public static function asciiQrCode(string $input): string
    {
        $baseUrl = 'https://techortech.com/asciiqr.php?i=&t=';
        $divIdToExtract = 'QRAscii';
        $urlEncoded = urlencode($input);

        $response = Http::get("{$baseUrl}{$urlEncoded}");
        $html = $response->body();
        //start div <div id="QRAscii" style="line-height:1em; letter-spacing:0em;  font-family:monospace">
        $start = strpos($html, "<div id=\"{$divIdToExtract}\"");
        $start = strpos($html, '>', $start) + 1;
        //end div </div>
        $end = strpos($html, '</div>', $start);
        $length = $end - $start;
        $asciiQrCode = substr($html, $start, $length);
        $asciiQrCode = str_replace('<br />', '', $asciiQrCode);

        $asciiQrCode = substr($asciiQrCode, 4);
        //convert the escaped html entities back to their original characters
        $asciiQrCode = html_entity_decode($asciiQrCode);
        return $asciiQrCode;
    }
}
