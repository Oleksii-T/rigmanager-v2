<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Spatie\Activitylog\LogOptions;
use App\Services\ProcessImageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory, LogsActivity;

    const POST_IMG_RESIZES = [
        300 => 300
    ];
    const AVATAR_RESIZES = [
        200 => 200
    ];
    const USER_BANNER_RESIZES = [
        1500 => 390
    ];

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

            // delete resized images
            $resizes = self::getAllResize();
            foreach ($resizes as $w => $h) {
                $path = $model->compressed($w, $h, true);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('models')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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
        if ($this->size > 1000000) {
            return number_format($this->size / 1000000, 2) . ' Mb';
        } elseif ($this->size > 1024) {
            return number_format($this->size / 1000, 2) . ' Kb';
        } else {
            return $this->size . ' b';
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

    // get path or url of compressed image
    public function compressed($w, $h=null, $asPath=false)
    {
        $h ??= $w;
        $path = ProcessImageService::getCompressedName($w, $h, $this->path);

        if ($asPath) {
            return $path;
        }

        if (!file_exists($path)) {
            return $this->url;
        }

        return ProcessImageService::getCompressedName($w, $h, $this->url);;
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
            ->editColumn('size', function ($model) {
                return $model->getSize();
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

    public static function getAllResize()
    {
        return self::POST_IMG_RESIZES + self::AVATAR_RESIZES + self::USER_BANNER_RESIZES;
    }
}
