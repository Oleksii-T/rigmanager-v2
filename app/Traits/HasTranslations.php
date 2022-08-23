<?php

namespace App\Traits;

use App\Models\Translation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait HasTranslations
{
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function translated($field, $locale=null)
    {
        $currLocale = LaravelLocalization::getCurrentLocale();
        $translations = $this->translations->where('field', $field);
        $defLocale = 'en';

        $result = $translations->where('locale', $locale??$currLocale)->value('value');

        if (!$result && !$locale) {
            $result = $translations->where('locale', $defLocale)->value('value');
        }

        return $result;
    }

    public function getLocalizedRouteKey($locale)
    {
        return $this->localed('slug');
    }

    public function saveTranslations($fieldsTranslations)
    {
        $translatables = self::TRANSLATABLES;
        foreach ($fieldsTranslations as $field => $translations) {
            if (!in_array($field, $translatables)) {
                continue;
            }
            foreach ($translations as $locale => $value) {
                $value ??= '';
                $this->translations()->updateOrCreate(
                    [
                        'field' => $field,
                        'locale' => $locale
                    ],
                    [
                        'field' => $field,
                        'locale' => $locale,
                        'value' => $value
                    ]
                );
            }
        }
    }

    public function purgeTranslations()
    {
        $this->translations()->delete();
    }
}
