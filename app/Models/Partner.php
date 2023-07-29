<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;
use App\Traits\HasAttachments;

class Partner extends Model
{
    use HasAttachments;

    protected $fillable = [
        'user_id',
        'order',
        'link'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeAttachments();
        });
    }

    public function image()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return $user ? '<a href="'.route('admin.partners.edit', $user).'">'.$user->name.'</a>' : '';
            })
            ->addColumn('image', function ($model) {
                $img = $model->image;
                return $img ? '<img src="'.$img->url.'" alt="">' : 'no';
            })
            ->editColumn('link', function ($model) {
                $link = $model->link;
                return $link ? '<a href="'.$link.'">'.$link.'</a>' : '';
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'partners'
                ])->render();
            })
            ->rawColumns(['user', 'image', 'link', 'action'])
            ->make(true);
    }
}
