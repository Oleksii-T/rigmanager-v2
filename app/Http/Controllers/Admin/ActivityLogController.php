<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
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

        // dd($request->subject_type);

        $activity = Activity::query()
            ->when($request->log_name, function ($q) {
                $q->where('log_name', request()->log_name);
            })
            ->when($request->log_name && $request->event[$request->log_name], function ($q) {
                $q->where('event', request()->event[request()->log_name]);
            })
            ->when($request->subject_type, function ($q) {
                $q->where('subject_type', request()->subject_type == '-' ? null : request()->subject_type);
            })
            ->when($request->subject_type && ($request->subject_id[$request->subject_type]??false), function ($q) {
                $q->where('subject_id', request()->subject_id[request()->subject_type]);
            })
            ->when($request->causer_type, function ($q) {
                $q->where('causer_type', request()->causer_type == '-' ? null : request()->causer_type);
            })
            ->when($request->causer_type && $request->causer_id[$request->causer_type]??false, function ($q) {
                $q->where('causer_id', request()->causer_id[request()->causer_type]);
            });

        return $this->dataTable($activity);
    }

    private function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('causer', function ($model) {
                return $model->causer_type
                    ? $model->causer_type . ':' . $model->causer_id
                    : '';
            })
            ->addColumn('subject', function ($model) {
                return $model->subject_type
                    ? $model->subject_type . ':' . $model->subject_id
                    : '';
            })
            ->editColumn('description', function ($model) {
                if ($model->description == $model->event) {
                    return '';
                }
                return view('admin.activity-logs.description-cell', [
                    'id' => $model->id,
                    'd' => $model->description
                ]);
            })
            ->editColumn('properties', function ($model) {
                if ($model->properties->isEmpty()) {
                    return '';
                }
                return view('admin.activity-logs.properties-cell', [
                    'id' => $model->id,
                    'props' => $model->properties->toArray(),
                    'raw' => $model->getAttribute('properties')
                ]);
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->rawColumns(['description', 'properties'])
            ->make(true);
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