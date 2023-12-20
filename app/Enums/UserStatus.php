<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum UserStatus:int
{
    use EnumTrait;

    case BANNED = 1;
    case LIMITED = 2;

    public static function getReadable($val)
    {
        return match ($val) {
            self::BANNED => 'Banned',
            self::LIMITED => 'Limited',
        };
    }

}
