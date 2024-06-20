<?php

namespace App\Models;

use App\Enums\PostType;
use App\Enums\PostGroup;
use App\Traits\Viewable;
use App\Traits\HasAttachments;
use App\Traits\HasTranslations;
use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Post extends Model
{
    use HasFactory, HasTranslations, HasAttachments, SoftDeletes, Viewable, LogsActivityBasic;

    protected $fillable = [
        'user_id',
        'category_id',
        'group',
        'origin_lang',
        'status',
        'type',
        'condition',
        'duration',
        'cost_per',
        'auto_translate',
        'is_double_cost',
        'is_tba',
        'is_active',
        'is_trashed',
        'is_urgent',
        'amount',
        'country',
        'manufacturer',
        'manufacture_date',
        'part_number',
        'scraped_url'
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    protected $casts = [
        'group' => PostGroup::class,
        'type' => PostType::class
    ];

    const STATUSES = [
        'pre-approved',
        'approved',
        'pending',
        'draft',
        'rejected'
    ];

    const DURATIONS = [
        '1m',
        '2m',
        'unlim'
    ];

    const CONDITIONS = [
        'new',
        'refurbished',
        'used',
        'for-parts'
    ];

    const TRANSLATABLES = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'slug'
    ];

    const PER_PAGE = 25;

    protected static function boot()
    {
        parent::boot();

        static::forceDeleting(function ($model) {
            $model->purgeAttachments();
            $model->purgeTranslations();
        });
    }

    // overload laravel`s method for route key generation
    public function getRouteKey()
    {
        $s = $this->slug;
        if (!$s) {
            \Log::error("Slug for post $this->id not found");
        }
        return $s;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteBy()
    {
        return $this->belongsToMany(User::class, UserFavPost::class)->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', __FUNCTION__)->orderBy('order', 'asc');
    }

    public function costs()
    {
        return $this->hasMany(PostCost::class);
    }

    public function documents()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', __FUNCTION__);
    }

    public function scopeEquipment($query)
    {
        return $query->where('group', PostGroup::EQUIPMENT);
    }

    public function scopeService($query)
    {
        return $query->where('group', PostGroup::SERVICE);
    }

    public function scopeActive($query, bool $is=true)
    {
        return $query->where('is_active', $is);
    }

    public function scopeVisible($query, $is=true)
    {
        $hidePending = Setting::get('hide_pending_posts', true, true);

        if ($is) {
            $query->where('is_active', true);
            $query->where('is_trashed', false);

            if ($hidePending) {
                return $query->whereIn('status', ['approved', 'pre-approved']);
            }
        } else {
            $query->where('is_active', false);
            $query->where('is_trashed', true);

            if ($hidePending) {
                return $query->where('status', 'pending');
            }
        }

        return $query;
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFilter($posts, array $filters)
    {
        return self::applyFilters($posts, $filters);
    }

    public function scopeCostByCurrency($query, string $curreny)
    {
        // TODO add column cost_by_currency (join post_costs);

        return $query;
    }

    public function slug(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function title(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function description(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function metaTitle(): Attribute
    {
        return $this->getTranslatedAttr('meta_title');
    }

    public function metaDescription(): Attribute
    {
        return $this->getTranslatedAttr('meta_description');
    }

    public function countryReadable(): Attribute
    {
        return new Attribute(
            get: fn () => trans("countries.$this->country"),
        );
    }

    public function cost(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getCost('eq', false)
        );
    }

    public function costFrom(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getCost('from', false)
        );
    }

    public function costTo(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getCost('to', false)
        );
    }

    public function currency(): Attribute
    {
        return new Attribute(fn () => $this->costs()->where('is_default', true)->value('currency'));
    }

    public function costReadable(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!$this->is_double_cost) {
                    return $this->getCost('eq',true);
                }

                $cost = $this->cost_from ? $this->getCost('from',true,null,false) : null;

                if ($this->cost_to) {
                    $cost = $cost ? "$cost - " : "__ - ";
                    $cost .= $this->getCost('to',true,null,false);
                }

                if (!$cost) {
                    return '';
                }

                if ($this->cost_per) {
                    $cost .= " per $this->cost_per";
                }

                return $cost;
            }
        );
    }

    public function getCost($type, $readable, $currency=null, $withPer=true)
    {
        if (!$currency) {
            $requestedCurr = request()->currency;
            if (array_key_exists($requestedCurr, currencies())) {
                $currency = $requestedCurr;
            }
        }

        $costM = $currency
            ? $this->costs->where('type', $type)->where('currency', $currency)->first()
            : $this->costs->where('type', $type)->where('is_default', true)->first();

        if (!$costM) {
            return null;
        }

        if (!$readable) {
            return $costM->cost;
        }

        $symbol = currencies($currency??$costM->currency);

        $cost = $symbol . number_format($costM->cost, 2);

        if ($withPer && $this->cost_per) {
            $cost .= " per $this->cost_per";
        }

        return $cost;
    }

    public function thumbnail($defaultImg=true)
    {
        $img = $this->images->first();

        return $img;
    }

    public function original($field)
    {
        return $this->translated($field, $this->origin_lang);
    }

    public function compileViews()
    {
        return [];
    }

    public function saveCosts($input)
    {
        dlog("Post@saveCosts"); //! LOG
        if ($input['is_double_cost']??false) {
            dlog(" is double"); //! LOG
            $costs = [
                'eq' => null,
                'from' => $input['cost_from']??null,
                'to' => $input['cost_to']??null,
            ];
        } else {
            dlog(" is single :("); //! LOG
            if (($input['cost_to']??false) && !($input['cost_from']??false)) {
                $input['cost'] = $input['cost_to'];
            }
            if (!($input['cost_to']??false) && ($input['cost_from']??false)) {
                $input['cost'] = $input['cost_from'];
            }
            $costs = [
                'eq' => $input['cost']??null,
                'from' => null,
                'to' => null,
            ];
        }
        $baseCurrency = $input['currency'];

        dlog(" res", $costs); //! LOG

        foreach ($costs as $type => $cost) {
            if (!$cost) {
                $this->costs()->where('type', $type)->delete();
                continue;
            };

            foreach (currencies() as $currency => $symbol) {
                PostCost::updateOrCreate(
                    [
                        'post_id' => $this->id,
                        'type' => $type,
                        'currency' => $currency
                    ],
                    [
                        'cost' => ExchangeRate::convert($baseCurrency, $currency, $cost),
                        'is_default' => $currency == $baseCurrency
                    ]
                );
            }
        }
    }

    public function getClassForProfile()
    {
        if ($this->is_trashed) {
            return 'profile-post-trashed';
        }

        if ($this->status == 'pending' && postsMustBeApproved()) {
            return 'profile-post-pending';
        }

        if (!$this->is_active) {
            return 'profile-post-hidden';
        }
    }

    public function generateMetaTitle()
    {
        return $this->generateMetaTitleHelper($this->title, $this->category->name);
    }

    public function generateMetaDescription()
    {
        $d = $this->description;

        if (!$d) {
            return $this->generateMetaTitle();
        }

        return $this->generateMetaDescriptionHelper($d);
    }

    public function getApprovingClass()
    {
        if ($this->status == 'pending') {
            $cl = 'text-warning';
        } else if ($this->status == 'approved') {
            $cl = 'text-success';
        } else if ($this->status == 'pre-approved') {
            $cl = 'text-info';
        } else {
            $cl = 'text-danger';
        }

        return $cl;
    }

    public static function generateMetaTitleHelper(string $title, string $category)
    {
        return $title . ' - ' . $category . ' on rigmanagers.com';
    }

    public static function generateMetaDescriptionHelper(string $description)
    {
        $description = strip_tags($description);
        $maxSeoDesc = 140;
        $seoDescSufix = ' | rigmanagers.com';
        $seoDescSufixLength = strlen($seoDescSufix);
        $maxSeoDesc -= $seoDescSufixLength;

        return strlen($description) > $maxSeoDesc
            ? (substr($description, 0, $maxSeoDesc) . "...$seoDescSufix")
            : $description . $seoDescSufix;
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
            })
            ->addColumn('category', function ($model) {
                $c = $model->category;
                return '<a href="'.route('admin.categories.edit', $c).'">'.$c->name.'</a>';
            })
            ->editColumn('is_active', function ($model) {
                return $model->is_active
                    ? '<span class="badge badge-success">yes</span>'
                    : '<span class="badge badge-warning">no</span>';
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->adminFormat();
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->adminFormat();
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'posts'
                ])->render();
            })
            ->rawColumns(['user', 'category', 'is_active', 'action'])
            ->make(true);
    }

    public static function allSlugs($ignore=null)
    {
        return Translation::query()
            ->where('translatable_type', self::class)
            ->where('translatable_id', '!=', $ignore)
            ->where('field', 'slug')
            ->pluck('value')
            ->toArray();
    }

    public static function getSorts()
    {
        return [
            'latest' => trans('ui.sortNew'),
            'cheap' => trans('ui.sortCheap'),
            'expensive' => trans('ui.sortExpensive'),
            'views' => trans('ui.sortViews')
        ];
    }

    public static function conditionReadable($condition)
    {
        switch ($condition) {
            case 'new':
                return trans('posts.conditions.new');
            case 'refurbished':
                return trans('posts.conditions.refurbished');
            case 'used':
                return trans('posts.conditions.used');
            case 'for-parts':
                return trans('posts.conditions.for-parts');
        }
    }

    public static function legalTypeReadable($legalType)
    {
        switch ($legalType) {
            case 'private':
                return trans('posts.legal-types.private');
            case 'business':
                return trans('posts.legal-types.business');
        }
    }

    public static function durationReadable($duration)
    {
        switch ($duration) {
            case '1m':
                return trans('ui.activeOneMonth');
            case '2m':
                return trans('ui.activeTwoMonth');
            case 'unlim':
                return trans('ui.activeForever');
        }
    }

    // get countries which has at least one post
    public static function countries()
    {
        return cache()->remember('posts.countries', 60*20, function() {
            $cs = self::visible()->pluck('country')->unique();
            $countries = [];
            foreach ($cs as $c) {
                $countries[$c] = trans("countries.$c");
            }
            return $countries;
        });
    }

    public static function applyFilters($posts, array $filters)
    {
        // dlog("Post@applyFilters"); //! LOG
        $conditions = $filters['conditions']??[];
        $types = $filters['types']??[];
        $urgent = count($filters['is_urgent']??[]) > 1 ? null : $filters['is_urgent'][0]??null;
        $sort = $filters['sorting']??null;
        $country = $filters['country']??null;
        $search = $filters['search']??null;
        $currency = $filters['currency']??null;
        $costFrom = $filters['cost_from']??null;
        $costTo = $filters['cost_to']??null;
        $author = $filters['author']??null;
        $category = $filters['category']??null;
        $status = $filters['status']??null;
        $hidePending = postsMustBeApproved();

        if ($status && request()->route()->getName() != 'profile.posts') {
            // status filter allowed only for personal posts
            $status = null;
        }

        if ($status == 'active') {
            $posts->where('is_active', true);
            if ($hidePending) {
                $posts->where('status', 'approved');
            }
        }
        if ($status == 'hidden') {
            $posts->where('is_active', false);
            if ($hidePending) {
                $posts->where('status', 'approved');
            }
        }
        if ($status == 'pending') {
            if ($hidePending) {
                $posts->where('status', 'pending');
            } else {
                $posts->where('id', 0);
            }
        }
        if ($status == 'rejected') {
            $posts->where('status', 'rejected');
        }
        if ($status == 'trashed') {
            $posts->where('is_trashed', true);
        }

        if (!($category instanceof Category)) {
            $category = Category::find($category);
        }

        if ($author){
            $user = User::where('slug', $author)->orWhere('id', $author)->first();
            if ($user) {
                $posts->where('user_id', $user->id);
            }
        }

        // append cost to query if cost filteting\sorting is used
        if (($currency && ($costFrom || $costTo)) || $sort == 'expensive' || $sort == 'cheap') {
            $posts->whereHas('costs');
        }

        if ($sort == 'expensive' || $sort == 'cheap') {
            // add cost to query to make sorting possible.
            // select smalles (and eq) cost if 'cheap' sorting selected
            // select biggest (and eq) cost if 'expensive' sorting selected
            $posts->leftJoin('post_costs', function ($join) use ($currency, $sort) {
                $c = $currency ?? 'usd'; // user may select sorting but not currency
                $types = $sort == 'expensive' ? ['to'] : ['from'];
                $types[] = 'eq';
                $join->on('posts.id', '=', 'post_costs.post_id');
                $join->on('currency', '=', \DB::raw("'$c'"));
                $join->whereIn('post_costs.type', $types);
            });
        }

        if ($currency && $costFrom) {
            // do not check 'posts.is_double_cost' because
            // we may only have cost with type 'eq' for single cost posts
            // and only costs with type 'to'+'from' for double cost posts
            $posts->where(function ($q) use($currency, $costFrom) {
                $q->whereHas('costs', fn ($q1) => $q1 // for single cost
                    ->where('currency', $currency)
                    ->where('type', 'eq')
                    ->where('cost', '>=', $costFrom)
                )
                ->orWhereHas('costs', fn ($q1) => $q1 // for double cost
                    ->where('currency', $currency)
                    ->where('type', 'to') // 'from' check is useless
                    ->where('cost', '>=', $costFrom
                ));
            });
        }

        if ($currency && $costTo) {
            $posts->where(function ($q) use($currency, $costTo) {
                $q->whereHas('costs', fn ($q1) => $q1 // for single cost
                    ->where('currency', $currency)
                    ->where('type', 'eq')
                    ->where('cost', '<=', $costTo)
                )
                ->orWhereHas('costs', fn ($q1) => $q1 // for double cost
                    ->where('currency', $currency)
                    ->where('type', 'from') // 'to' check is useless
                    ->where('cost', '<=', $costTo)
                );
            });
        }

        if ($category) {
            $posts->whereIn('category_id', $category->getChildsIds());
        }

        if ($country) {
            $posts->where('country', $country);
        }

        if ($search) {
            $fullSearch = false;
            $posts->whereHas('translations', function ($q) use ($search, $fullSearch) {
                if ($fullSearch) {
                    $q->whereIn('field', ['title', 'description']);
                } else {
                    $q->where('field', 'title');
                }
                $q->where('locale', LaravelLocalization::getCurrentLocale());
                $q->where('value', 'like', "%$search%");
            });
        }

        if ($conditions && count($conditions) < count(Post::CONDITIONS)) {
            $posts->whereIn('condition', $conditions);
        }

        if ($types && count($types) < count(PostType::values())) {
            $posts->whereIn('posts.type', $types);
        }

        if ($urgent !== null) {
            $posts->where('is_urgent', $urgent);
        }

        switch ($sort) {
            case 'latest':
                $posts->latest('posts.created_at');
                break;
            case 'cheap':
                $posts->orderBy('post_costs.cost');
                break;
            case 'expensive':
                $posts->orderByDesc('post_costs.cost');
                break;
            case 'views':
                $posts->orderByDesc('views_count');
                break;
            default:
                $posts->latest('posts.created_at');
                break;
        }

        return $posts;
    }
}
