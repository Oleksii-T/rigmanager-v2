<?php

namespace App\Enums;

use \App\Traits\EnumTrait;

enum NotificationGroup:int
{
    use EnumTrait;

    case MANUAL = 0;
    case DAILY_POSTS_VIEWS = 1;
    case DAILY_CONTACS_VIEWS = 2;
    case DAILY_PROFILE_VIEWS = 3;
    case WEEKLY_POSTS_VIEWS = 4;
    case WEEKLY_CONTACS_VIEWS = 5;
    case WEEKLY_PROFILE_VIEWS = 6;
    case MAILER_SEND = 7;
    case IMPORT_SUCCESS = 8;
    case IMPORT_FAIL = 9;
    case POST_APPROVED = 10;
    case POST_REJECTED = 11;
    case SUB_CREATED = 12;
    case SUB_CANCELED = 13;
    case SUB_END_SOON = 14;
    case SUB_ENDED = 15;
    case SUB_EXTENDED = 16;
    case PRICE_REQ_RECIEVED = 17;

    public static function getReadable($val)
    {
        return match ($val) {
            self::MANUAL => 'Manual',
            self::DAILY_POSTS_VIEWS => 'Daily Posts Views',
            self::DAILY_CONTACS_VIEWS => 'Daily Contacs Views',
            self::DAILY_PROFILE_VIEWS => 'Daily Profile Views',
            self::WEEKLY_POSTS_VIEWS => 'Weekly Posts Views',
            self::WEEKLY_CONTACS_VIEWS => 'Weekly Contacs Views',
            self::WEEKLY_PROFILE_VIEWS => 'Weekly Profile Views',
            self::MAILER_SEND => 'Mailer Send',
            self::IMPORT_SUCCESS => 'Import Success',
            self::IMPORT_FAIL => 'Import Fail',
            self::POST_APPROVED => 'Post Approved',
            self::POST_REJECTED => 'Post Rejected',
            self::SUB_CREATED => 'Sub created',
            self::SUB_CANCELED => 'Sub canceed',
            self::SUB_END_SOON => 'Sub end soon',
            self::SUB_ENDED => 'Sub ended',
            self::SUB_EXTENDED => 'Sub extended',
            self::PRICE_REQ_RECIEVED => 'Price request recieved',
        };
    }

}
