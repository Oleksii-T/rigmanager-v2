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
            'name' => 'Open Exchange Rates',
            'settings' => [
                'openexchangerates_app_id' => 'App ID',
            ]
        ],
        [
            'name' => 'GCP',
            'settings' => [
                'gcp_project' => 'GCP project',
                'gcp_id' => 'GCP id',
                'gcp_key' => 'GCP key'
            ]
        ],
        [
            'name' => 'Google auth',
            'settings' => [
                'google_auth_enabled' => 'Google Auth Enabled',
                'google_client_id' => 'Google client id',
                'google_client_secret' => 'Google client secret',
                'google_redirect' => 'Google redirect',
            ]
        ],
        [
            'name' => 'Facebook auth',
            'settings' => [
                'facebook_auth_enabled' => 'Facebook Auth Enabled',
                'facebook_client_id' => 'Facebook client id',
                'facebook_client_secret' => 'Facebook client secret',
                'facebook_redirect' => 'Facebook redirect',
            ]
        ],
        [
            'name' => 'Twitter auth',
            'settings' => [
                'twitter_auth_enabled' => 'Twitter Auth Enabled',
                'twitter_client_id' => 'Twitter client id',
                'twitter_client_secret' => 'Twitter client secret',
                'twitter_redirect' => 'Twitter redirect',
            ]
        ],
        [
            'name' => 'LinkedIn auth',
            'settings' => [
                'linkedin_auth_enabled' => 'LinkedIn Auth Enabled',
            ]
        ],
        [
            'name' => 'Apple auth',
            'settings' => [
                'apple_auth_enabled' => 'Apple Auth Enabled',
            ]
        ],
    ];

    public static function get($key, $onlyValue = true, $cache = false)
    {
        try {
            if ($cache) {
                $cKey = "settings.$key";
                $fallbackVal = 'no-value-from-cache';
                $value = cache()->get($cKey, $fallbackVal);
                if ($value != $fallbackVal) {
                    return $value;
                }
            }

            $setting = self::where('key', $key)->first();
            $setting = $onlyValue ? $setting->data['value'] : $setting->data;

            if ($cache) {
                cache()->put($cKey, $setting, 60);
            }

            return $setting;
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
