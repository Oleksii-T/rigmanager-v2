<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        Route::bind('post', function ($value) {
            return Post::getBySlug($value);
        });

        Route::bind('subscription_plan', function ($value) {
            return SubscriptionPlan::getBySlug($value);
        });

        Route::bind('category', function ($value) {
            return Category::getBySlug($value);
        });

        Route::bind('blog', function ($value) {
            return Blog::getBySlug($value);
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'save-activity'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web'])
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('feedbacks', function (Request $request) {
            return Limit::perMinute(2);
        });
    }
}
