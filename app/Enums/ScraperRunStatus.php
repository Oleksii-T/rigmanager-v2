<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum ScraperRunStatus:int
{
    use EnumTrait;

    case PENDING = 1;
    case IN_PROGRESS = 2;
    case SUCCESS = 3;
    case ERROR = 4;

    public static function getReadable($val)
    {
        return match ($val) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In progress',
            self::SUCCESS => 'Success',
            self::ERROR => 'Error',
        };
    }

}
