<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserInformation extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'phones',
        'emails',
        'website',
        'facebook',
        'linkedin',
        'whatsapp',
    ];

    protected $casts = [
        'phones' => 'array',
        'emails' => 'array',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
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
}
