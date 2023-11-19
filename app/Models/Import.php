<?php

namespace App\Models;

use App\Traits\HasAttachments;
use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Import extends Model
{
    use HasAttachments, LogsActivityBasic;

    protected $fillable = [
        'user_id',
        'status',
        'posts',
        'settings',
    ];

    protected $casts = [
        'posts' => 'array',
        'settings' => 'array'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FAILED = 'failed';
    const STATUS_DONE = 'done';

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeAttachments();
        });
    }

    public function file()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getExamplesDisk()
    {
        return Storage::disk('import-examples');
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
            })
            ->editColumn('posts', function ($model) {
                return count($model->posts??[]);
            })
            ->editColumn('status', function ($model) {
                if ($model->status == 'pending' || $model->status == 'processing') {
                    return '<span class="badge badge-warning">'.$model->status.'</span>';
                }

                if ($model->status == 'done') {
                    return '<span class="badge badge-success">Done</span>';
                }

                if ($model->status == 'failed') {
                    return '<span class="badge badge-danger">Failed</span>';
                }

                return 'undef';
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'imports',
                    'actions' => ['show']
                ])->render();
            })
            ->rawColumns(['user', 'status', 'action'])
            ->make(true);
    }
}
