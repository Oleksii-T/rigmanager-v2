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
            'name' => 'General',
            'settings' => [
                'hide_pending_posts' => 'Hide pending posts (1/0)',
                // 'sort_categories_by' => 'Categories sorting (alphabet/posts)',
                // 'hide_empty_categories' => 'Hide empty categories (1/0)',
            ]
        ],
        [
            'name' => 'Social login',
            'settings' => [
                'google_auth_enabled' => 'Google Auth Enabled',
                'google_client_id' => 'Google client id',
                'google_client_secret' => 'Google client secret',
                'google_redirect' => 'Google redirect',
                'facebook_auth_enabled' => 'Facebook Auth Enabled',
                'facebook_client_id' => 'Facebook client id',
                'facebook_client_secret' => 'Facebook client secret',
                'facebook_redirect' => 'Facebook redirect',
                'twitter_auth_enabled' => 'Twitter Auth Enabled',
                'twitter_client_id' => 'Twitter client id',
                'twitter_client_secret' => 'Twitter client secret',
                'twitter_redirect' => 'Twitter redirect',
                'linkedin_auth_enabled' => 'LinkedIn Auth Enabled',
                'apple_auth_enabled' => 'Apple Auth Enabled',
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
