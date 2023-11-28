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
    case SUB_END_TOMORROW = 14;
    case SUB_EXTENTION_FAILED = 15;
    case SUB_EXTENDED = 16;
    case PRICE_REQ_RECIEVED = 17;
    case SUB_CANCELED_TERMINATED_CAUSE_NEW = 18;
    case SUB_CANCELED_EXPIRED = 19;
    case SUB_TERMINATED_CAUSE_NEW = 20;
    case SUB_INCOMPLETED_EXPIRED = 21;
    case SUB_CREATED_INCOMPLETE = 22;
    case SUB_EXTENDED_INCOMPLETE = 23;
    case SUB_INCOMPLETED_PAID = 24;
    case SUB_RENEW_NEXT_WEEK = 25;
    case SUB_RENEW_TOMORROW = 26;
    case SUB_END_NEXT_WEEK = 27;

    // for admin
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
            self::SUB_CANCELED => 'Sub canceled',
            self::SUB_END_TOMORROW => 'Sub end soon',
            self::SUB_EXTENTION_FAILED => 'Sub extention failed cause payment error',
            self::SUB_EXTENDED => 'Sub extended',
            self::PRICE_REQ_RECIEVED => 'Price request recieved',
            self::SUB_CANCELED_TERMINATED_CAUSE_NEW => 'Sub canceled terminated because of new sub',
            self::SUB_CANCELED_EXPIRED => 'Sub canceled expired',
            self::SUB_TERMINATED_CAUSE_NEW => 'Sub terminated because of new sub',
            self::SUB_INCOMPLETED_EXPIRED => 'Sub terminated incomplete',
            self::SUB_CREATED_INCOMPLETE => 'Sub created as incomplete',
            self::SUB_EXTENDED_INCOMPLETE => 'Sub extended as incomplete',
            self::SUB_INCOMPLETED_PAID => 'Sub incomplete paid',
            self::SUB_RENEW_NEXT_WEEK => 'Sub renew next week',
            self::SUB_RENEW_TOMORROW => 'Sub renew tomorrow',
            self::SUB_END_NEXT_WEEK => 'Sub end next week',
        };
    }

}
