<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum FeedbackStatus:int
{
    use EnumTrait;

    case PENDING = 0;
    case CLIENT = 1;
    case ADV = 2;
    case TRASH = 3;

    public static function getReadable($val)
    {
        return match ($val) {
            self::PENDING => 'Pending',
            self::CLIENT => 'Client',
            self::TRASH => 'Trash',
            self::ADV => 'Ad',
        };
    }

}
