<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Yajra\DataTables\DataTables;

class Attachment extends Model
{
    use HasFactory;

    /**
     * @var array
     */
	protected $fillable = [
        'name',
        'group',
        'original_name',
        'type',
        'size',
        'order',
        'attachmentable_id',
        'attachmentable_type'
    ];

    protected $appends = [
        'url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $disk = self::disk($model->type);
            Storage::disk($disk)->delete($model->name);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachmentable()
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getSize()
    {
        if ($this->size > 1024) {
            return number_format($this->size / 1024, 2) . ' Mb';
        } else {
            return $this->size . ' Kb';
        }
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Storage::disk(self::disk($this->type))->url($this->name),
        );
    }

    public function path(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Storage::disk(self::disk($this->type))->path($this->name),
        );
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('preview', function ($model) {
                return $model->type == 'image' ? '<img src="'.$model->url.'" alt="">' : '';
            })
            ->addColumn('resource', function ($model) {
                return $model->attachmentable_type . ': ' . $model->attachmentable_id;
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'attachments',
                    'actions' => ['edit']
                ])->render();
            })
            ->rawColumns(['preview', 'action'])
            ->make(true);
    }

    public static function disk($type)
    {
        return match ($type) {
            'video' => 'avideos',
            'image' => 'aimages',
            'document' => 'adocuments',
            default => 'attachments',
        };
    }
}
