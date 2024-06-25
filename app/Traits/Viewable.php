<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;

trait Viewable
{
    public function views()
    {
        return $this->morphMany(Activity::class, 'subject')->where('log_name', 'models')->where('event', 'view');
    }

    public function viewsCount($unique=false, $bot=null, $fake=null)
    {
        // get all views query
        $query = $this->views();

        // get only unique views by IP
        if ($unique) {
            $query->selectRaw("COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(properties, '$.general_info.ip'))) AS unique_number_count");
        }

        // filter views by bots
        if ($bot === true) {
            $query->whereRaw("JSON_EXTRACT(properties, '$.general_info.agent_info.is_robot') = true");
        }
        if ($bot === false) {
            $query->whereRaw("JSON_EXTRACT(properties, '$.general_info.agent_info.is_robot') = false");
        }

        // filter views by fakeness (some views may be created manualy)
        if ($fake === true) {
            $query->whereRaw("JSON_EXTRACT(properties, '$.is_fake') = true");
        }
        if ($fake === false) {
            $query->whereRaw("JSON_EXTRACT(properties, '$.is_fake') = false");
        }

        // return the count
        return $unique ? $query->value('unique_number_count') : $query->count();
    }

    public function viewsStats()
    {
        $total = $this->views()->count();
        $cKey = 'views-stats-' . self::class . '-' . $this->id . '-' . $total;
        return cache()->rememberForever($cKey, function () use ($total) {
            $stats = [
                'total' => $total,
                'fake' => $this->viewsCount(false, null, true),
                'bots' => $this->viewsCount(false, true, null),
                'real' => $this->viewsCount(false, false, false),
                'ureal' => $this->viewsCount(true, false, false),
            ];
    
            return $stats;
        });
    }

    public function saveView($isFake=false)
    {
        $user = auth()->user();

        if ($user && $user->isAdmin() && \App\Models\Setting::get('admins_not_generating_views', true, true) == 1) {
            return;
        }

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
