<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Actions\MakeActivityLogTable;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Storage;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            $names = $this->getActivityGroups('log_name', 'event');
            $causers = $this->getActivityGroups('causer_type', 'causer_id');
            $subjects = $this->getActivityGroups('subject_type', 'subject_id');

            return view('admin.activity-logs.index', compact('names', 'causers', 'subjects'));
        }

        $tsDate = $request->ts_date ? Carbon::createFromTimestampMs($request->ts_date) : null;

        $activity = Activity::query()
            ->when($request->period, function ($q) {
                $period = explode(' - ', request()->period);
                $q->where('created_at', '>=', $period[0] . ' 00:00:00')->where('created_at', '<=', $period[1] . ' 23:59:59');
            })
            ->when($request->log_name, function ($q) {
                $q->where('log_name', request()->log_name);
            })
            ->when($request->log_name && $request->event && $request->event[$request->log_name], fn ($q) =>
                $q->where('event', $request->event[$request->log_name])
            )
            ->when($request->subject_type, fn ($q) =>
                $q->where('subject_type', $request->subject_type == '-' ? null : $request->subject_type)
            )
            ->when($request->subject_type && ($request->subject_id[$request->subject_type]??false), fn ($q) =>
                $q->where('subject_id', $request->subject_id[$request->subject_type])
            )
            ->when($request->causer_type, fn ($q) =>
                $q->where('causer_type', $request->causer_type == '-' ? null : $request->causer_type)
            )
            ->when($request->causer_type && $request->causer_id && $request->causer_id[$request->causer_type]??false, fn ($q) =>
                $q->where('causer_id', $request->causer_id[$request->causer_type])
            )
            ->when($tsDate, fn($q) =>
                $q->whereDate('created_at', $tsDate)
            );

        return MakeActivityLogTable::run($activity);
    }

    private function getActivityGroups($groupBy, $column)
    {
        $columns = $column . 's';
        $activities = Activity::query()
            ->selectRaw("$groupBy, GROUP_CONCAT(DISTINCT $column) as $columns")
            ->groupBy($groupBy)
            ->get();

        $result = [];
        foreach ($activities as $activity) {
            $result[$activity->$groupBy??'-'] = explode(',', $activity->$columns);
        }

        return $result;
    }
}
