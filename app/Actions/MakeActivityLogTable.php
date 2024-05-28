<?php

namespace App\Actions;

use Yajra\DataTables\DataTables;

class MakeActivityLogTable
{
    public static function run($query)
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