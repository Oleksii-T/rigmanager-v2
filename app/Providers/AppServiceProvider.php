<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Feedback;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
        if (!isdev()) {
            try {
                \Debugbar::disable();
            } catch (\Throwable $th) {}
        }

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
                'translations' => [],
                'stripe_public_key' => Setting::get('stripe_public_key', true, true),
                'recaptcha_key' => config('services.recaptcha.public_key'),
                'page_assists_config' => [
                    'importValidationErrors' => [
                        'error_amount' => 3
                    ],
                    'importCreate' => [
                        'show_after_seconds' => 60,
                        'route_name' => 'imports.create'
                    ],
                    'postCreate' => [
                        'show_after_seconds' => 60,
                        'route_name' => 'posts.create'
                    ],
                    // 'postShow' => [
                    //     'show_after_seconds' => 3,
                    //     'route_name' => 'posts.show'
                    // ],
                    // 'subscriptionCreate' => [
                    //     'show_after_seconds' => 3,
                    //     'route_name' => 'subscriptions.create'
                    // ],
                ]
            ];
            $translationsForJs = [
                'messages.inProgress',
                'messages.canNotChatToSelf',
                'messages.profile.canNotDeleteLastEmailContact',
                'messages.profile.maxContactEmails',
                'messages.profile.maxContactPhones',
                'messages.areYouSure',
                'messages.yesDeleteIt',
                'messages.yesMoveToTrash',
                'messages.canNotRevert',
                'messages.areYouSureMoveToTrash',
                'messages.trashedPostsAutoDeleted',
                'messages.clearFavsConfirmMessage',
                'messages.clearFavsConfirmBtn',
                'ui.email',
                'ui.phone',
                'ui.tba_modal',
                'ui.sendMessagePopupTitle',
                'ui.sendMessagePopupSendBtn',
                'ui.sendMessagePopupGotToChat',
            ];
            foreach ($translationsForJs as $t) {
                $data['translations'][str_replace('.', '_', $t)] = trans($t);
            }
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

        \Blade::directive('svg', function($arguments) {
            // Funky madness to accept multiple arguments into the directive
            list($path, $class) = array_pad(explode(',', trim($arguments, "() ")), 2, '');
            $path = trim($path, "' ");
            $class = trim($class, "' ");

            // Create the dom document as per the other answers
            $svg = new \DOMDocument();
            $svg->load(public_path($path));
            $svg->documentElement->setAttribute("class", $class);
            $output = $svg->saveXML($svg->documentElement);

            return $output;
        });
        \Blade::if('isSub', function($level=null) {
            $level = $level ?: null;
            $user = auth()->user();

            if (!$user) {
                return false;
            }

            return $user->isSub($level);
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
                    $pending = Post::active()->status('pending')->count();
                    if ($pending) {
                        $menu['label'] = $pending;
                    }
                } catch (\Throwable $th) {}
            }
            if (($menu['route']??null) == 'admin.feedbacks.index') {
                try {
                    $count = Feedback::where('status', \App\Enums\FeedbackStatus::PENDING)->count();
                    if ($count) {
                        $menu['label'] = $count;
                    }
                } catch (\Throwable $th) {}
            }
        }
        config(['adminlte.menu'=> $adminlteMenus]);

        Paginator::defaultView('components.pagination');

        $this->app->singleton('dumper', function ($app) {
            return new \App\Services\DumperService();
        });

        Carbon::mixin(new class {
            public function adminFormat()
            {
                return function () {
                    return $this->format('d/m/Y H:i');
                };
            }
        });
    }
}
