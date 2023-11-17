<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;

trait Viewable
{
    public function views()
    {
        return $this->morphMany(Activity::class, 'subject')->where('log_name', 'models')->where('event', 'view');
    }

    public function saveView()
    {
        activity('models')
            ->on($this)
            ->event('view')
            ->tap(function(\Spatie\Activitylog\Contracts\Activity $activity) {
                $activity->properties = [
                    'ip' => request()->ip(),
                    'agent' => request()->header('User-Agent'),
                    'is_fake' => false
                ];
            })
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
