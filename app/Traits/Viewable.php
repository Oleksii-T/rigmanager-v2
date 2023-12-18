<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;

trait Viewable
{
    public function views()
    {
        return $this->morphMany(Activity::class, 'subject')->where('log_name', 'models')->where('event', 'view');
    }

    public function saveView($isFake=false)
    {
        return activity('models')
            ->on($this)
            ->event('view')
            ->withProperties(infoForActivityLog() + [
                'is_fake' => $isFake
            ])
            ->log('');
    }

    public static function getAllViews($fake=false)
    {
        return Activity::query()
            ->where('log_name', 'models')
            ->where('event', 'view')
            ->where('properties->is_fake', $fake)
            ->where('subject_type', self::class);
    }
}
