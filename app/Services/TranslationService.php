<?php

namespace App\Services;

use Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Google\Cloud\Translate\TranslateClient;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TranslationService
{
    private $translator;

    public function __construct()
    {
        $key = Setting::get('gcp_key');
        $this->translator = new TranslateClient(['key' => $key]);
    }

    public function detectLanguage($text)
    {
        if (app()->environment('local')) {
            return 'en';
        }

        try {
            $res = $this->translator->detectLanguage($text)['languageCode'];
            $locales = array_keys(LaravelLocalization::getLocalesOrder());
            if (!in_array($res, $locales)) {
                $res = 'en';
            }
        } catch (\Throwable $th) {
            Log::error("Can not detect language.", [
                'text' => $text,
                'user' => auth()->id() ?? '0',
                'error' => $th->getMessage(),
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);
            $res = 'en';
        }

        return $res;
    }

    public function translate($text, $to)
    {
        if (app()->environment('local')) {
            return "$to | $text";
        }

        try {
            $res = $this->translator->translate($text, ['target' => $to, 'format'=>'text'])['text'];
        } catch (\Throwable $th) {
            Log::error("Can not translate text.", [
                'text' => $text,
                'to' => $to,
                'user' => auth()->id() ?? '0',
                'error' => $th->getMessage(),
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);
            $res = $text;
        }

        return $res;
    }
}
