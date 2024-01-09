<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum CategoryType:int
{
    use EnumTrait;

    case EQUIPMENT = 1;
    case SERVICE = 2;

    public static function getReadable($val)
    {
        return match ($val) {
            self::EQUIPMENT => 'Equipment',
            self::SERVICE => 'Service',
        };
    }

}
