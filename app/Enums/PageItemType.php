<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum PageItemType:int
{
    case TEXT = 1;
    case TEXTAREA = 2;
    case RICHTEXT = 3;

    public static function getReadable($val)
    {
        return match ($val) {
            self::TEXT => 'Text',
            self::TEXTAREA => 'Textare',
            self::RICHTEXT => 'Richtext',
        };
    }

}
