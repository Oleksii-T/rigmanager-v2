<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'stripe_secret_key' => 'sk_test_51Jw4DPD0NmLmOo5v9tlVZIloUC95D7PAIfNnRAb6LmE5DrwSguzmWoeXhPWJ7InIgHmkzBj8Gnlp1610nQuOHAPZ00eJV2ap0d',
            'currency' => 'usd',
            'currency_sign' => '$',
            'openexchangerates_app_id' => '7a4176e35ebb4899afac6c3be38c1350',
            'email_feedback' => '1',
            'email_feedback_to' => 'admin@mail.com',
            'google_client_id' => '1035671449664-f3a1h3h6d6953qs9pp3fseag9gl81skb.apps.googleusercontent.com',
            'google_client_secret' => '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',
            'google_redirect' => 'http://localhost/auth/callback/google',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
