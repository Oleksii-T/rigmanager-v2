<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum ScraperPostStatus:int
{
    use EnumTrait;

    case PENDING = 1;
    case PUBLISHED = 2;
    case CANCELED = 3;

    public static function getReadable($val)
    {
        return match ($val) {
            self::PENDING => 'Pending',
            self::PUBLISHED => 'Published',
            self::CANCELED => 'Canceled',
        };
    }

}
