<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];

    const EDATABLE_SETTINGS = [
        [
            'name' => 'Social login',
            'settings' => [
                'google_client_id' => 'Google client id',
                'google_client_secret' => 'Google client secret',
                'google_redirect' => 'Google redirect',
                'facebook_client_id' => 'Facebook client id',
                'facebook_client_secret' => 'Facebook client secret',
                'facebook_redirect' => 'Facebook redirect',
                'twitter_client_id' => 'Twitter client id',
                'twitter_client_secret' => 'Twitter client secret',
                'twitter_redirect' => 'Twitter redirect',
            ]
        ]
    ];

    public static function get($key, $onlyValue = true)
    {
        try {
            $setting = self::where('key', $key)->first();
            return $onlyValue ? $setting->data['value'] : $setting->data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function set($key, $value)
    {
        try {
            self::updateOrCreate([
                'key' => $key
            ], [
                'key' => $key,
                'data' => is_string($value) ? ['value' => $value] : $value
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
