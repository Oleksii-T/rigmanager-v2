<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum NotificationType:int
{
    use EnumTrait;

    case INFO = 0;

    public static function getReadable($val)
    {
        return match ($val) {
            self::INFO => 'Info',
        };
    }

}
