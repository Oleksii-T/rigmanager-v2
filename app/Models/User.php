<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasAttachments;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasAttachments, Notifiable;

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
        'phone',
        'bio',
        'website',
        'facebook',
        'linkedin',
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

    // Get the route key for the model.
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function socials()
    {
        return $this->hasMany(UserSocial::class);
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
        return $this->belongsToMany(Post::class, 'user_fav_posts')->withTimestamps();
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

    public function facebookName(): Attribute
    {
        return new Attribute(
            get: function () {
                try {
                    $res = parse_url($this->facebook)['path'];
                    $res = explode('/', $res);
                    $res = array_filter($res);
                    $res = end($res);
                    if ($res == 'profile.php') {
                        $res = 'facebook.com';
                    }
                    abort_if(!$res, 500);
                } catch (\Throwable $th) {
                    $res = $this->facebook;
                }

                return $res;
            },
        );
    }

    public function linkedinName(): Attribute
    {
        return new Attribute(
            get: function () {
                try {
                    $res = parse_url($this->linkedin)['path'];
                    $resA = explode('/', $res);
                    $resA = array_filter($resA);
                    $res = end($resA);
                    abort_if(!$res, 500);
                    if ($res == 'about') {
                        $res = $resA[count($resA)-2];
                    }
                } catch (\Throwable $th) {
                    $res = $this->linkedin;
                }

                return $res;
            },
        );
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
        $id = $this->id;
        return !!$this->messages()
            ->where(function ($q) use ($id){
                $q
                    ->where('user_id', $id)
                    ->orWhere('reciever_id', $id);
            })
            ->count();
    }

    public function sendPasswordResetNotification($token)
    {
        $url = route('password.reset', ['token'=>$token, 'email'=>$this->email]);

        Mail::to($this)->send(new PasswordReset($url));
    }

    public function lastCurrency()
    {
        // $c = $this->posts()->whereNotNull('currency')->latest()->select('currency')->first();
        $c = $this->posts()->whereHas('costs')->latest()->first()?->costs()->where('is_default', true)->value('currency');

        return $c ?? 'usd';
    }

    public function makeNotif($resource=null, $group=null, $type=null, $data=[])
    {
        $group ??= \App\Enums\NotificationGroup::MANUAL;
        $type ??= Notification::groupToType($group);

        return $this->notifications()->crete([
            'notifiable_id' => $resource->id??null,
            'notifiable_type' => $resource ? get_class($resource) : null,
            'group' => $group,
            'type' => $type,
            'data' => $data,
        ]);
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
