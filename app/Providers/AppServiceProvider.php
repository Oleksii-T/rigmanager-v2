<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use App\Models\Post;
use App\Models\Feedback;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

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
            $user = auth()->user();
            $cashTime = 60*5;

            $view->with(
                'currentUser',
                auth()->user()
            );

            $view->with(
                'currentLocale',
                LaravelLocalization::getCurrentLocale()
            );

            $data = [
                'csrf' => csrf_token(),
                'route_name' => \Route::currentRouteName(),
                // some more public data to use in JS
            ];
            if ($user) {
                $data['user'] = [
                    'name' => $user->name,
                    'email' => $user->email
                ];
            }
            $view->with('LaravelDataForJS', json_encode($data));
        });


        Builder::macro('toSqlWithBindings', function () {
            $bindings = array_map(
                fn ($value) => is_numeric($value) ? $value : "'{$value}'",
                $this->getBindings()
            );

            return Str::replaceArray('?', $bindings, $this->toSql());
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
            if (($menu['route']??null) == 'admin.posts.index') {
                try {
                    $pending = Post::status('pending')->count();
                    if ($pending) {
                        $menu['label'] = $pending;
                    }
                } catch (\Throwable $th) {}
            }
            if (($menu['route']??null) == 'admin.feedbacks.index') {
                try {
                    $count = Feedback::where('is_read', false)->count();
                    if ($count) {
                        $menu['label'] = $count;
                    }
                } catch (\Throwable $th) {}
            }
        }
        config(['adminlte.menu'=> $adminlteMenus]);

        Paginator::defaultView('components.pagination');
    }
}
