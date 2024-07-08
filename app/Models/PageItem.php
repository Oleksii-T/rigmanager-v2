<?php

namespace App\Models;

use App\Enums\PageItemType;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PageItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'type',
        'group'
    ];

    protected $casts = [
        'type' => PageItemType::class,
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const TRANSLATABLES = [
        'value',
    ];

    public function value(): Attribute
    {
        return $this->getTranslatedAttr('value');
    }

    public function values(): Attribute
    {
        $values = [];

        foreach (\LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
            $values[$localeCode] = $this->translated('value', $localeCode);
        }

        return Attribute::make(fn () => $values);
    }
}
