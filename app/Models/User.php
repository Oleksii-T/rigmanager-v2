<?php

namespace App\Models;

use App\Traits\Viewable;
use App\Mail\PasswordReset;
use App\Traits\HasAttachments;
use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasAttachments, Notifiable, Viewable, LogsActivityBasic;

    const ONLINE_MINUTES = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'country',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->info()->create([]);
        });
    }

    // Get the route key for the model.
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function socials()
    {
        return $this->hasMany(UserSocial::class);
    }

    public function info()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Post::class, UserFavPost::class)->withTimestamps();
    }

    public function mailers()
    {
        return $this->hasMany(Mailer::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }

    public function avatar()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('group', 'avatar');
    }

    public function banner()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('group', 'banner');
    }

    public function scopeOnline($query, bool $is=true)
    {
        return $query->where('last_active_at', '>=', now()->subMinutes(self::ONLINE_MINUTES));
    }

    public function isAdmin()
    {
        return (bool)$this->roles()->where('name', 'admin')->count();
    }

    public function messages()
    {
        $id = $this->id;
        $messages = Message::query()
            ->where(function ($q) use ($id){
                $q
                    ->where('reciever_id', $id)
                    ->orWhere('user_id', $id);
            })
            ->latest();

        return $messages;
    }

    public function hasChatWith($uid)
    {
        $messages = $this->messages()
            ->where(function ($q) use ($uid){
                $q
                    ->where('user_id', $uid)
                    ->orWhere('reciever_id', $uid);
            })
            ->count();

        return (bool)$messages;
    }

    public function sendPasswordResetNotification($token)
    {
        $url = route('password.reset', ['token'=>$token, 'email'=>$this->email]);

        Mail::to($this)->send(new PasswordReset($url));
    }

    public function getEmails($i=null)
    {
        $emails = $this->info->emails??[];
        $emails = array_filter($emails);

        if (!$emails) {
            $emails = [$this->email];
        }

        if ($i!==null) {
            return $emails[$i]??null;
        }

        return $emails;
    }

    public function lastCurrency()
    {
        // $c = $this->posts()->whereNotNull('currency')->latest()->select('currency')->first();
        $c = $this->posts()->whereHas('costs')->latest()->first()?->costs()->where('is_default', true)->value('currency');

        return $c ?? 'usd';
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('name', function ($model) {
                return $model->name;
            })
            ->addColumn('posts', function ($model) {
                return $model->posts->count();
            })
            ->editColumn('last_active_at', function ($model) {
                return $model->last_active_at->diffForHumans();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'users',
                    'actions' => ['show', 'edit', 'destroy']
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
