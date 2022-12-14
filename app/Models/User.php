<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use App\Traits\HasAttachments;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasAttachments, Notifiable;

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
        'bio',
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
        return $this->hasMany(SocialUser::class);
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

    public function imports()
    {
        return $this->hasMany(Import::class);
    }

    public function avatar()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function isAdmin()
    {
        return (bool)$this->roles()->where('name', 'admin')->count();
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
