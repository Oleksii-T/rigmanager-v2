<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class GoogleSearchConsoleService
{
    private $key;

    public function __construct()
    {
        $this->key = Setting::get('gcp_key');
    }

    public function inspectUrl(string $url)
    {
        $cKey = "google-search-console-api-$url";
        $res = cache()->get($cKey);

        if ($res) {
            return $res;
        }

        $url = "https://searchconsole.googleapis.com/v1/urlInspection/index:inspect?key=$this->key";

        $response = Http::post($url, [
            'inspectionUrl' => $url,
            'key' => $this->key,
            'languageCode' => 'en'
        ]);

        $res = $response->json();

        cache()->put($cKey, $res, 60*60*24);

        return $res;
    }
}
