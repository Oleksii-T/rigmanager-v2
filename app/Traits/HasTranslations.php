<?php

namespace App\Traits;

use App\Models\Translation;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        $result = $translations->where('locale', $locale??$currLocale)->value('value');

        if (!$result && !$locale) {
            $this->failed_translation = true;
            $origin = $this->origin_lang;
            $result = $translations->where('locale', $origin ? $origin : 'en')->value('value');
        }

        return $result;
    }

    public function saveTranslations($fieldsTranslations)
    {
        $translatables = self::TRANSLATABLES;
        foreach ($fieldsTranslations as $field => $translations) {
            if (!in_array($field, $translatables)) {
                continue;
            }
            foreach ($translations as $locale => $value) {
                if (!$value) {
                    continue;
                }
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

    public function getTranslatedAttr($attr)
    {
        return new Attribute(
            get: fn () => $this->translated($attr)
        );
    }

    public static function getBySlug($slug)
    {
        return Translation::query()
            ->where('translatable_type', self::class)
            // ->where('locale', LaravelLocalization::getCurrentLocale())
            ->where('field', 'slug')
            ->where('value', $slug)
            ->firstOrFail()
            ->translatable;
    }
}
