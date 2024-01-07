<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum PostGroup:int
{
    use EnumTrait;

    case EQUIPMENT = 0;
    case SERVICE = 1;

    public static function getReadable($val)
    {
        return match ($val) {
            self::EQUIPMENT => 'Equipment',
            self::SERVICE => 'Service',
        };
    }

}
