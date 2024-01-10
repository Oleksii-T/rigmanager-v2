<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Traits\HasAttachments;
use App\Traits\HasTranslations;
use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasTranslations, HasAttachments, LogsActivityBasic;

    protected $fillable = [
        'type',
        'category_id',
        'is_active'
    ];

    protected $casts = [
        'type' => CategoryType::class
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const TRANSLATABLES = [
        'name',
        'slug',
        'suggestions'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeAttachments();
            $model->purgeTranslations();
        });
    }

    // overload laravel`s method for route key generation
    public function getRouteKey()
    {
        return $this->slug;
    }

    public function image()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

    public function scopeEquipment($query)
    {
        return $query->where('type', CategoryType::EQUIPMENT);
    }

    public function scopeService($query)
    {
        return $query->where('type', CategoryType::SERVICE);
    }

    public function slug(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function name(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function suggestions(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function scopeActive($query, bool $is=true)
    {
        $query->where('is_active', $is);
    }

    /**
     * get posts which belongs to current category or to any child category
     */
    public function postsAll($get=false)
    {
        $res = Post::whereIn('category_id', $this->getChildsIds());

        return $get ? $res->get() : $res;
    }

    public function getUrl($keepParams=false)
    {
        $r = 'search';
        $d = $this->parents(true);
        return $keepParams ? qroute($r, $d) : route($r, $d);
    }

    /**
     * get current categories and all parents as an array
     */
    public function parents($reverse=false)
    {
        $res = self::parentsHelper($this);
        return $reverse ? array_reverse($res) : $res;
    }

    /**
     * get all childs categories
     */
    public function childsAll($get=false)
    {
        $ids = $this->getChildsIds();
        unset($ids[array_search($this->id, $ids)]);
        $res = self::whereIn('id', $ids);

        return $get ? $res->get() : $res;
    }

    /**
     * get all childs ids including current
     */
    public function getChildsIds()
    {
        $array = array($this->id);
        if ($this->childs->isEmpty()) {
            return $array;
        }
        return array_merge($array, $this->getChildrenIds($this->childs));
    }

    public static function dataTable($query, $request)
    {
        if ($request->search && $request->search['value']) {
            $value = $request->search['value'];
            $query->whereHas('translations', function ($q) use ($value) {
                $q->where('field', 'name')->where('value', 'like', "%$value%");
            });
        }
        if ($request->parent) {
            $query->where('category_id', $request->parent);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->status !== null) {
            $query->where('is_active', (bool)$request->status);
        }
        if ($request->has_childs !== null) {
            if ($request->has_childs) {
                $query->whereHas('childs');
            } else {
                $query->whereDoesntHave('childs');
            }
        }
        if ($request->has_parent !== null) {
            if ($request->has_parent) {
                $query->whereNotNull('category_id');
            } else {
                $query->whereNull('category_id');
            }
        }

        return DataTables::of($query)
            ->addColumn('parent', function ($model) {
                $parent = $model->parent;
                if ($parent) {
                    return '<a href="'.route('admin.categories.edit', $parent).'">'.$parent->name.'</a>';
                }
                return '';
            })
            ->editColumn('is_active', function ($model) {
                return $model->is_active
                    ? '<span class="badge badge-success">yes</span>'
                    : '<span class="badge badge-warning">no</span>';
            })
            ->addColumn('childs', function ($model) {
                return $model->childs()->count();
            })
            ->addColumn('posts', function ($model) {
                return $model->postsAll()->count();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->adminFormat();
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'categories'
                ])->render();
            })
            ->rawColumns(['parent', 'is_active', 'action'])
            ->make(true);
    }

    private static function parentsHelper($category, $result=[])
    {
        $result[] = $category;
        $parent = $category->parent;
        if ($parent) {
            $result = self::parentsHelper($parent, $result);
        }

        return $result;
    }

    public static function getDefault()
    {
        return self::find(1);
    }

    public static function getLevels($equipment=true)
    {
        $categs = self::active()->whereNull('category_id')->with('childs.childs');
        $categs = $equipment ? $categs->equipment() : $categs->service();
        $categs = $categs->get();

        $first = [];
        $second = [];
        $third = [];

        foreach ($categs as $firstC) {
            $first[] = $firstC;
            foreach ($firstC['childs']??[] as $secondC) {
                if (isset($second[$firstC->id])) {
                    $second[$firstC->id][] = $secondC;
                } else {
                    $second[$firstC->id] = [$secondC];
                }
                foreach ($secondC['childs']??[] as $thirdC) {
                    if (isset($third[$secondC->id])) {
                        $third[$secondC->id][] = $thirdC;
                    } else {
                        $third[$secondC->id] = [$thirdC];
                    }
                }
            }
        }

        return [$first, $second, $third];
    }

    private function getChildrenIds($subcategories)
    {
        $array = array();
        foreach ($subcategories as $subcategory) {
            array_push($array, $subcategory->id);
            if ($subcategory->childs->isNotEmpty()) {
                $array = array_merge($array, $this->getChildrenIds($subcategory->childs));
            }
        }
        return $array;
    }
}
