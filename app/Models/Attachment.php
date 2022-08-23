<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Attachment extends Model
{
    /**
     * @var array
     */
	protected $fillable = [
        'name',
        'group',
        'original_name',
        'type',
        'size',
        'attachmentable_id',
        'attachmentable_id_type'
    ];

    protected $appends = [
        'url'
    ];

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $disk = self::disk($model->type);
            Storage::disk($disk)->delete($model->name);
        });
    }

    public function url(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Storage::disk(self::disk($this->type))->url($this->name),
        );
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
