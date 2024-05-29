<?php

namespace App\Actions;

use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class MakeActivityLogTable
{
    public static function run($request, $query)
    {
        $tsDate = $request->ts_date ? Carbon::createFromTimestampMs($request->ts_date) : null;

        $query->when($request->period, function ($q) {
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
                    'desc' => $model->description
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
                return $model->created_at->adminFormat();
            })
            ->rawColumns(['description', 'properties'])
            ->make(true);
    }
}
