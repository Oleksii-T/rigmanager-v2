<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;
use App\Traits\HasTranslations;
use App\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Events\PostCreated;
use App\Jobs\MailerProcessNewPost;

class Post extends Model
{
    use HasFactory, HasTranslations, HasAttachments;

    protected $fillable = [
        'user_id',
        'category_id',
        'origin_lang',
        'status',
        'type',
        'condition',
        'duration',
        'is_active',
        'is_urgent',
        'amount',
        'country',
        'manufacturer',
        'manufacture_date',
        'part_number',
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const STATUSES = [
        'approved',
        'pending',
        'draft',
        'banned'
    ];

    const DURATIONS = [
        '1m',
        '2m',
        'unlim'
    ];

    const TYPES = [
        'sell',
        'buy',
        'rent',
        'lease'
    ];

    const CONDITIONS = [
        'new',
        'used',
        'for-parts'
    ];

    const TRANSLATABLES = [
        'title',
        'description',
        'slug'
    ];

    const PER_PAGE = 25;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeAttachments();
            $model->purgeTranslations();
        });

        static::created(function ($model) {
            $hidePending = Setting::get('hide_pending_posts', true, true);
            if (!$hidePending) {
                MailerProcessNewPost::dispatch($model);
            }
        });

        static::updated(function ($model) {
            $hidePending = Setting::get('hide_pending_posts', true, true);
            $nowActive = $model->getAttribute('is_active');
            $wasActive = $model->getOriginal('is_active');
            $wasStatus = $model->getAttribute('status');
            $nowStatus = $model->getOriginal('status');

            $sendBecauseActive = !$hidePending && !$wasActive && $nowActive;
            $sendBecauseStatus = $hidePending && $wasStatus == 'pending' && $nowStatus == 'approved';

            if ($sendBecauseActive || $sendBecauseStatus) {
                MailerProcessNewPost::dispatch($model);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', 'images');
    }

    public function costs()
    {
        return $this->hasMany(PostCost::class);
    }

    public function documents()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', 'documents');
    }

    public function scopeActive($query, bool $is=true)
    {
        return $query->where('is_active', $is);
    }

    public function scopeVisible($query)
    {
        $hidePending = Setting::get('hide_pending_posts', true, true);
        $query->where('is_active', true);

        if ($hidePending) {
            return $query->where('status', 'approved');
        }

        return $query;
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFilter($posts, array $filters)
    {
        $conditions = $filters['conditions']??[];
        $types = $filters['types']??[];
        $urgent = $filters['is_urgent'][0]??null;
        $import = $filters['is_import'][0]??null;
        $sort = $filters['sorting']??null;
        $country = $filters['country']??null;
        $search = $filters['search']??null;
        $currency = $filters['currency']??null;
        $costFrom = $filters['cost_from']??null;
        $costTo = $filters['cost_to']??null;
        $author = $filters['author']??null;
        $category = $filters['category']??null;
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
            $posts->whereHas('costs')->leftJoin('post_costs', function ($join) use ($currency) {
                $c = $currency ?? 'usd'; // user may select sorting but not currency
                $join->on('posts.id', '=', 'post_costs.post_id');
                $join->on('currency', '=', \DB::raw("'$c'"));
            });
        }

        if ($currency && $costFrom) {
            $posts->where('post_costs.cost', '>=', $costFrom);
        }

        if ($currency && $costTo) {
            $posts->where('post_costs.cost', '<=', $costFrom);
        }

        if ($category) {
            $posts->whereIn('category_id', $category->getChildsIds());
        }

        if ($country) {
            $posts->where('country', $country);
        }

        if ($search) {
            $posts->whereHas('translations', function ($q) use ($search){
                $q->whereIn('field', ['title', 'description'])
                    ->where('locale', LaravelLocalization::getCurrentLocale())
                    ->where('value', 'like', "%$search%");
            });
        }

        if ($conditions && count($conditions) < count(Post::CONDITIONS)) {
            $posts->whereIn('condition', $conditions);
        }

        if ($types && count($types) < count(Post::TYPES)) {
            $posts->whereIn('type', $types);
        }

        if ($urgent !== null) {
            $posts->where('is_urgent', $urgent);
        }

        if ($import !== null) {
            $posts->where('is_import', $import);
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

    public function countryReadable(): Attribute
    {
        return new Attribute(
            get: fn () => trans("countries.$this->country"),
        );
    }

    public function cost(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getCost(false)
        );
    }

    public function costReadable(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->getCost(true)
        );
    }

    public function getCost($readable, $currency=null)
    {
        if (!$currency) {
            $requestedCurr = request()->currency;
            if (array_key_exists($requestedCurr, currencies())) {
                $currency = $requestedCurr;
            }
        }

        $costM = $currency
            ? $this->costs->where('currency', $currency)->first()
            : $this->costs->where('is_default', true)->first();

        if (!$costM) {
            return null;
        }

        if (!$readable) {
            return $costM->cost;
        }

        $symbol = currencies($currency??$costM->currency);

        return $symbol . number_format($costM->cost, 2);
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
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format(env('ADMIN_DATETIME_FORMAT'));
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

    public function saveCosts($input)
    {
        $cost = $input['cost']??null;
        $baseCurrency = $input['currency'];

        if (!$cost) {
            $this->costs()->delete();
            return;
        }

        foreach (currencies() as $currency => $symbol) {
            PostCost::updateOrCreate(
                [
                    'post_id' => $this->id,
                    'currency' => $currency
                ],
                [
                    'post_id' => $this->id,
                    'currency' => $currency,
                    'cost' => ExchangeRate::convert($baseCurrency, $currency, $input['cost']),
                    'is_default' => $currency == $baseCurrency
                ]
            );
        }
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

    public static function typeReadable($type)
    {
        switch ($type) {
            case 'sell':
                return trans('posts.types.sell');
            case 'buy':
                return trans('posts.types.buy');
            case 'rent':
                return trans('posts.types.rent');
            case 'lease':
                return trans('posts.types.lease');
        }
    }

    public static function conditionReadable($condition)
    {
        switch ($condition) {
            case 'new':
                return trans('posts.conditions.new');
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
}
