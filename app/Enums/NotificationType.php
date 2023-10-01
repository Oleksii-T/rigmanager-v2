<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum NotificationType:int
{
    use EnumTrait;

    case INFO = 0;
    case SUCCESS = 1;
    case WARNING = 2;
    case DANGER = 3;

    public static function getReadable($val)
    {
        return match ($val) {
            self::INFO => 'Info',
            self::SUCCESS => 'Success',
            self::WARNING => 'Warning',
            self::DANGER => 'Danger',
        };
    }

}
