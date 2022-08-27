<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // set global blade variables
        \View::composer('*', function($view) {
            $cashTime = 5;

            $view->with(
                'currentUser',
                auth()->user()
            );
        });

        // load config values from db
        config(['services.google.client_id'=> Setting::get('google_client_id')]);
        config(['services.google.client_secret'=> Setting::get('google_client_secret')]);
        config(['services.google.redirect'=> Setting::get('google_redirect')]);

        config(['services.twitter.client_id'=> Setting::get('twitter_client_id')]);
        config(['services.twitter.client_secret'=> Setting::get('twitter_client_secret')]);
        config(['services.twitter.redirect'=> Setting::get('twitter_redirect')]);

        config(['services.facebook.client_id'=> Setting::get('facebook_client_id')]);
        config(['services.facebook.client_secret'=> Setting::get('facebook_client_secret')]);
        config(['services.facebook.redirect'=> Setting::get('facebook_redirect')]);

        // add dynamic values to adminlte menu
        $adminlteMenus = config('adminlte.menu');
        foreach ($adminlteMenus as &$menu) {
            if ($menu['route'] == 'admin.posts.index') {
                $pending = Post::status('pending')->count();
                if ($pending) {
                    $menu['label'] = $pending;
                }
            }
        }
        config(['adminlte.menu'=> $adminlteMenus]);
    }
}
