<?php

namespace App\Models;

use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use LogsActivityBasic;

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
                'fake_autotranslation' => 'Fake auto-translation',
                'detect_post_language' => 'Detect post original language',
                'post_id_in_empty_mailer_text' => 'Post id in empty mailer text',
                // 'sort_categories_by' => 'Categories sorting (alphabet/posts)',
                // 'hide_empty_categories' => 'Hide empty categories (1/0)',
            ]
        ],
        [
            'name' => 'Image Processing',
            'settings' => [
                'convert_uploaded_post_images_to_webp' => 'Convert uploaded post images to .webp',
                'add_water_mark_to_uploaded_post_images' => 'Add water mark to uploaded post images',
                'resized_versions_of_uploaded_post_images' => 'Generate resized versions of post images',
                'resized_uploaded_post_images' => 'Ensure max width of 1920px for post images',
                'optimize_user_avatar' => 'Optimize user avatar (.webp + resize)',
                'optimize_user_banner' => 'Optimize user banner (.webp + resize)',
            ]
        ],
        [
            'name' => 'Open Exchange Rates',
            'settings' => [
                'openexchangerates_app_id' => 'App ID',
            ]
        ],
        [
            'name' => 'Emails to non-registered users',
            'settings' => [
                'non_reg_send_price_req' => 'Send price requests',
                'non_reg_send_notif_analytics_to_email' => 'Dublicate daily and weekly notifications (with post/contact/profile views) to email',
            ]
        ],
        [
            'name' => 'Notifications',
            'settings' => [
                'notif_daily_posts_views_min' => 'Minimum daily post views to notify',
                'notif_daily_contacts_views_min' => 'Minimum daily contacts views to notify',
                'notif_daily_profile_views_min' => 'Minimum daily profile views to notify',
                'notif_weekly_posts_views_min' => 'Minimum weekly post views to notify',
                'notif_weekly_contacts_views_min' => 'Minimum weekly contacts views to notify',
                'notif_weekly_profile_views_min' => 'Minimum weekly profile views to notify',
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
        [
            'name' => 'Stripe',
            'settings' => [
                'stripe_public_key' => 'Public Key',
                'stripe_secret_key' => 'Secret Key',
                'stripe_product' => 'Product ID'
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
            $value = $value
                ? is_string($value) ? ['value' => $value] : $value
                : ['value' => $value];
            self::updateOrCreate([
                'key' => $key
            ], [
                'key' => $key,
                'data' => $value
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
