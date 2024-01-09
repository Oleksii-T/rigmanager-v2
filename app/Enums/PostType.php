<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum PostType:int
{
    use EnumTrait;

    case SELL = 0;
    case BUY = 1;
    case RENT = 2;
    case LEASE = 3;
    case PROVIDE = 4;
    case REQUEST = 5;

    public static function forEquipment()
    {
        $all = self::all();
        $types = [self::SELL->value, self::BUY->value, self::RENT->value, self::LEASE->value];
        return array_filter($all, fn ($t) => in_array($t, $types), ARRAY_FILTER_USE_KEY);
    }

    public static function forService()
    {
        $all = self::all();
        $types = [self::PROVIDE->value,self::REQUEST->value];
        return array_filter($all, fn ($t) => in_array($t, $types), ARRAY_FILTER_USE_KEY);
    }

    public static function getReadable($val)
    {
        return match ($val) {
            self::SELL => trans('posts.types.sell'),
            self::BUY => trans('posts.types.buy'),
            self::RENT => trans('posts.types.rent'),
            self::LEASE => trans('posts.types.lease'),
            self::PROVIDE => trans('posts.types.provide'),
            self::REQUEST => trans('posts.types.request'),
        };
    }

}
