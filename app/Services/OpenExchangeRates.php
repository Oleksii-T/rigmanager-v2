<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class OpenExchangeRates
{
    private $id;
    private $host;

    public function __construct()
    {
        $this->id = Setting::get('openexchangerates_app_id');
        $this->host = 'https://openexchangerates.org/api/';
    }

    public function latest($base=null)
    {
        $base = $base ? strtoupper($base) : null;

        $response = Http::get($this->host . 'latest.json', [
            'base' => $base,
            'app_id' => $this->id
        ]);

        return $response->json();
    }
}
