<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Faq extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'order'
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const TRANSLATABLES = [
        'question',
        'answer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeTranslations();
        });
    }

    public function question(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function answer(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('question', function ($model) {
                return $model->question;
            })
            ->addColumn('answer', function ($model) {
                return substr(strip_tags($model->answer), 0, 200) . '...';
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'faqs'
                ])->render();
            })
            // ->rawColumns(['user', 'status', 'action'])
            ->make(true);
    }
}
