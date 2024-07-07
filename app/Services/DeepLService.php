<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class DeepLService
{
    private $key;

    public function __construct()
    {
        $this->key = Setting::get('deepl_key');
    }

    public function translate(string|array $text, $lang)
    {
        $cKey = "deepl-translation-" . json_encode($text) . '-' . $lang;
        $res = cache()->get($cKey);

        if ($res) {
            return $res;
        }

        $bulkTranslation = is_array($text);
        $url = 'https://api-free.deepl.com/v2/translate';

        $response = Http::withHeaders([
            'Authorization' => "DeepL-Auth-Key $this->key"
        ])->post($url, [
            'text' => $bulkTranslation ? $text : [$text],
            'source_lang' => 'EN',
            'target_lang' => $lang
        ]);

        $res = $response->json()['translations'];

        if ($bulkTranslation) {
            $res = array_column($res, 'text');
        } else {
            $res = $res[0]['text'];
        }

        cache()->put($cKey, $res, 60*60*24);

        return $res;
    }
}
