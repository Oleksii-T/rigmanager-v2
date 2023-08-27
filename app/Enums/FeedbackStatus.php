<?php

namespace App\Enums;

enum FeedbackStatus:int 
{
    case PENDING = 0;
    case READ = 1;
    case TRASH = 3;

    public function readable()
    {
        return self::getReadable($this);
    }

    public static function all()
    {
        $cases = self::cases();
        $res = [];

        foreach ($cases as $case) {
            $res[$case->value] = self::getReadable($case);
        }

        return $res;
    }

    public static function getReadable($val)
    {
        return match ($val) {
            self::PENDING => 'Pending',
            self::READ => 'Read',
            self::TRASH => 'Trash'
        };
    }

}
