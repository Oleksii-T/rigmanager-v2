<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\Translation;
use App\Models\Category;
use App\Imports\PostsImport;

class ValidatePostsImport
{
    public static function run($file)
    {
        $pages = \Excel::toArray(new PostsImport, $file);
        $rows = $pages[0]; // get first excel page

        // basic structure
        if (count($rows) < 3 || count($rows[0]) < 14) {
            return [0, trans('messages.importStructureError')];
        }

        $rows = array_slice($rows, 2); // remove the header from import file
        $rows = array_slice($rows, 0, 500); // remove all but first 500 rows

        foreach ($rows as $i => $row) {
            if (!$row[1]) {
                break;
            }
            try {
                self::row($row);
            } catch (\Throwable $th) {
                return [$i+1, $th->getMessage()];
            }
        }

        return [null, null];
    }

    public static function row($row)
    {
        self::title($row[1]);
        self::description($row[2]);
        self::category($row[3]);
        self::images($row[4]);
        self::type($row[5]);
        self::condition($row[6]);
        self::amount($row[7]);
        self::manufacturer($row[8]);
        self::manufactureDate($row[9]);
        self::partNumber($row[10]);
        self::cost($row[11]);
        self::country($row[12]);
        self::duration($row[13]);
    }

    public static function title($val)
    {
        if (!$val || strlen($val) > 255) {
            abort(422, trans('messages.import.errors.title'));
        }
    }

    public static function description($val)
    {
        if (!$val) {
            abort(422, trans('messages.import.errors.description-empty'));
        }

        if (strlen($val) > 9000) {
            abort(422, trans('messages.import.errors.description-too-long'));
        }
    }

    public static function category($val)
    {
        if (!$val) {
            abort(422, trans('messages.import.errors.category'));
        }

        $slugs = cache()->remember('posts-import.categs-slugs', 60, function() {
            return Translation::where('translatable_type', Category::class)->where('field', 'slug')->where('locale', 'en')->pluck('value');
        });

        if ($slugs->contains($val)) {
            return true;
        }

        $slugs = cache()->remember('posts-import.categs-titles', 60, function() {
            return Translation::where('translatable_type', Category::class)->where('field', 'name')->where('locale', 'en')->pluck('value');
        });

        if (!$slugs->contains($val)) {
            abort(422, trans('messages.import.errors.category'));
        }
    }

    public static function images($val)
    {
        if ($val === null) {
            return;
        }

        $links = explode(' ', $val);

        foreach ($links as $link) {
            if (!$link) {
                continue;
            }

            $pattern = '/\s*/m';
            $replace = '';
            $link = preg_replace( $pattern, $replace, $link);
            $link = trim($link);

            if (!$link) {
                continue;
            }

            if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
                abort(422, trans('messages.import.errors.images') . " 1 '$link'");
            }
            if (
                !str_ends_with($link, '.jpg') &&
                !str_ends_with($link, '.jpeg') &&
                !str_ends_with($link, '.png') &&
                !str_ends_with($link, '.webp')
            ) {
                abort(422, trans('messages.import.errors.images') . ' 2');
            }
        }
    }

    public static function type($val)
    {
        if (!$val || !in_array(strtolower($val), Post::TYPES)) {
            abort(422, trans('messages.import.errors.type'));
        }
    }

    public static function condition($val)
    {
        if ($val === null) {
            return;
        }

        if (!in_array(strtolower($val), Post::CONDITIONS)) {
            abort(422, trans('messages.import.errors.type'));
        }
    }

    public static function amount($val)
    {
        if ($val === null) {
            return;
        }

        $int = intval($val);
        if ($val != $int || $int < 1) {
            abort(422, trans('messages.import.errors.amount'));
        }
    }

    public static function manufacturer($val)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            abort(422, trans('messages.import.errors.manufacturer'));
        }
    }

    public static function manufactureDate($val)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            abort(422, trans('messages.import.errors.manufactureDate'));
        }
    }

    public static function partNumber($val)
    {
        if ($val === null) {
            return;
        }

        if (strlen($val) > 70) {
            abort(422, trans('messages.import.errors.partNumber'));
        }
    }

    public static function cost($val)
    {
        if ($val === null) {
            return;
        }

        $currencies = array_values(currencies());
        $currency = $val[0];
        $val = substr($val, 1);

        if (!in_array($currency, $currencies) || $val != floatval($val)) {
            abort(422, trans('messages.import.errors.cost'));
        }
    }

    public static function country($val)
    {
        if ($val === null) {
            return;
        }

        $countries = array_keys(countries());
        if (!in_array(strtolower($val), $countries)) {
            abort(422, trans('messages.import.errors.country'));
        }
    }

    public static function duration($val)
    {
        if (!$val || !in_array(strtolower($val), Post::DURATIONS)) {
            abort(422, trans('messages.import.errors.duration'));
        }
    }
}
