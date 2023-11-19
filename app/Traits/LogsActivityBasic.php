<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogsActivityBasic
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('models')
            ->logAll()
            ->logExcept(['updated_at', 'last_active_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $props = $activity->properties->toArray();
        $props['general_info'] = infoForActivityLog();
        $activity->properties = collect($props);
    }
}
