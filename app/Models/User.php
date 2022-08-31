<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use App\Traits\HasAttachments;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasAttachments;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

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

    public function favPosts()
    {
        return $this->belongsToMany(Post::class, 'user_fav_posts')->withTimestamps();
    }

    public function avatar()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('group', 'avatar');
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

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('name', function ($model) {
                return $model->name;
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'users'
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
