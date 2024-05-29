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

        $activity = Activity::query();

        return MakeActivityLogTable::run($request, $activity);
    }

    private function getActivityGroups($groupBy, $column)
    {
        $groups = Activity::distinct()->pluck('subject_type');
        $result = [];
        foreach ($groups as $group) {
            $result[$group] = Activity::distinct()->where('subject_type', $group)->pluck('subject_id')->toArray();
        }

        return $result;

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
