<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum NotificationGroup:int
{
    use EnumTrait;

    case MANUAL = 0;
    case DAILY_POSTS_VIEWS = 1;  // info
    case DAILY_CONTACS_SHOWS = 2;  // info
    case WEEKLY_POSTS_VIEWS = 3;  // info
    case WEEKLY_CONTACS_SHOWS = 4;  // info
    case MAILER_SEND = 5;  // info
    case IMPORT_SUCCESS = 6;  // success
    case IMPORT_FAIL = 7;  // danger
    case POST_APPROVED = 8;  // success
    case POST_REJECTED = 9;  // danger
    case SUB_CREATED = 12;  // success
    case SUB_CANCELED = 13;  // danger
    case SUB_END_SOON = 10;  // warning
    case SUB_ENDED = 11;  // danger
    case SUB_EXTENDED = 12;  // success

    public static function getReadable($val)
    {
        return match ($val) {
            self::MANUAL => 'Manual',
            self::DAILY_POSTS_VIEWS => 'Daily Posts Views',
            self::DAILY_CONTACS_SHOWS => 'Daily Contacs Shows',
            self::WEEKLY_POSTS_VIEWS => 'Weekly Posts Views',
            self::WEEKLY_CONTACS_SHOWS => 'Weekly Contacs Shows',
            self::MAILER_SEND => 'Mailer Send',
            self::IMPORT_SUCCESS => 'Import Success',
            self::IMPORT_FAIL => 'Import Fail',
            self::POST_APPROVED => 'Post Approved',
            self::POST_REJECTED => 'Post Rejected',
        };
    }

}
