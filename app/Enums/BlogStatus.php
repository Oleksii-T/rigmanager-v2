<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum BlogStatus:int
{
    use EnumTrait;

    case DRAFT = 0;
    case PUBLISHED = 1;
    case TRASHED = 2;

    public static function getReadable($val)
    {
        return match ($val) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::TRASHED => 'Trashed'
        };
    }

}
