<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\MailerController;
use App\Http\Controllers\ImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('dev/{action}', [\App\Http\Controllers\DevController::class, 'action']);

Route::get('auth/social/{provider}', [SocialAuthController::class, 'redirect'])->name('auth.social');
Route::get('auth/callback/{provider}', [SocialAuthController::class, 'callback']);

Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('index');
})->name('logout');

Route::middleware(['localeSessionRedirect', 'localizationRedirect'])->prefix(LaravelLocalization::setLocale())->group(function () {

    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('about', [PageController::class, 'about'])->name('about');
    Route::get('faq', [PageController::class, 'faq'])->name('faq');
    Route::get('privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('terms', [PageController::class, 'terms'])->name('terms');
    Route::get('site-map', [PageController::class, 'siteMap'])->name('site-map');
    Route::get('categories', [PageController::class, 'categories'])->name('categories');
    Route::get('import/rules', [PageController::class, 'importRules'])->name('import-rules');
    Route::get('catalog/{slug1?}/{slug2?}/{slug3?}', [SearchController::class, 'index'])->name('search');

    Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/{blog}', [BlogController::class, 'show'])->name('blog.show');
    Route::put('blog/{blog}/view', [BlogController::class, 'view'])->name('blog.view');

    Route::get('plans', [SubscriptionPlanController::class, 'index'])->name('plans.index');
    Route::get('plans/subscribe', [SubscriptionPlanController::class, 'subscribe'])->name('plans.subscribe');

    Route::get('contact-us', [FeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('contact-us', [FeedbackController::class, 'store'])->middleware('throttle:feedbacks', 'recaptcha')->name('feedbacks.store');

    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::put('posts/{post}/view', [PostController::class, 'view'])->name('posts.view');
    Route::put('users/{user}/view', [UserController::class, 'view'])->name('users.view');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('{user}', [UserController::class, 'show'])->name('show');
    });

    Route::middleware('auth')->group(function () {

        Route::middleware('verified')->group(function () {

            Route::prefix('users')->name('users.')->group(function () {
                Route::get('{user}/contacts', [UserController::class, 'contacts'])->name('contacts');
            });

            Route::prefix('imports')->name('imports.')->group(function () {
                Route::get('', [ImportController::class, 'index'])->name('index');
                Route::get('create', [ImportController::class, 'create'])->name('create');
                Route::get('store', [ImportController::class, 'store'])->name('store');
                Route::get('downloads', [ImportController::class, 'downloadExample'])->name('download-example');
                Route::get('{import}/posts', [ImportController::class, 'posts'])->name('posts');
                Route::get('{import}/download', [ImportController::class, 'download'])->name('download');
                Route::post('{import}/posts/delete', [ImportController::class, 'postsDelete'])->name('posts.delete');
                Route::post('{import}/posts/deactivate', [ImportController::class, 'postsDeactivate'])->name('posts.deactivate');
                Route::post('{import}/posts/activate', [ImportController::class, 'postsActivate'])->name('posts.activate');
                Route::post('', [ImportController::class, 'store'])->name('store');
            });

            Route::prefix('posts')->name('posts.')->group(function () {
                Route::get('create', [PostController::class, 'create'])->name('create');
                Route::get('{post}/edit', [PostController::class, 'edit'])->name('edit');
                Route::get('{post}/views', [PostController::class, 'views'])->name('views');
                Route::get('{post}/translations', [PostController::class, 'translationsEdit'])->name('translations.edit');
                Route::post('', [PostController::class, 'store'])->name('store');
                Route::post('{post}/translations/report', [PostController::class, 'translationsReport'])->name('translations.report');
                Route::post('{post}/tba', [PostController::class, 'tba'])->name('tba');
                Route::put('{post}/add-to-fav', [PostController::class, 'addToFav'])->name('add-to-fav');
                Route::put('{post}/toggle-active', [PostController::class, 'toggle'])->name('toggle');
                Route::put('{post}/translations', [PostController::class, 'translationsUpdate'])->name('translations.update');
                Route::put('{post}', [PostController::class, 'update'])->name('update');
                Route::put('{post}/recover', [PostController::class, 'recover'])->name('recover');
                Route::delete('{post}/trash', [PostController::class, 'trash'])->name('trash');
                Route::delete('{post}', [PostController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('mailers')->name('mailers.')->group(function () {
                Route::get('', [MailerController::class, 'index'])->name('index');
                Route::get('{mailer}/edit', [MailerController::class, 'edit'])->name('edit');
                Route::post('', [MailerController::class, 'store'])->name('store');
                Route::put('deactivate', [MailerController::class, 'deactivate'])->name('deactivate');
                Route::put('{mailer}/toggle-active', [MailerController::class, 'toggle'])->name('toggle');
                Route::put('{mailer}', [MailerController::class, 'update'])->name('update');
                Route::delete('', [MailerController::class, 'destroyAll'])->name('destroy-all');
                Route::delete('{mailer}', [MailerController::class, 'destroy'])->name('destroy');
            });
            Route::resource('mailers', MailerController::class)->except('show');

            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('/', [ProfileController::class, 'index'])->name('index');
                Route::get('credentials', [ProfileController::class, 'credentials'])->name('credentials');
                Route::get('posts/{slug1?}/{slug2?}/{slug3?}', [ProfileController::class, 'posts'])->name('posts');
                Route::get('favorites/{slug1?}/{slug2?}/{slug3?}', [ProfileController::class, 'favorites'])->name('favorites');
                Route::get('subscription', [ProfileController::class, 'subscription'])->name('subscription');
                Route::get('chat', [ProfileController::class, 'chat'])->name('chat');
                Route::post('chat/{user}', [ProfileController::class, 'message'])->name('chat.store');
                Route::post('posts/action', [ProfileController::class, 'action']);
                Route::put('/', [ProfileController::class, 'update'])->name('update');
                Route::put('password', [ProfileController::class, 'password'])->name('password');
                Route::put('login', [ProfileController::class, 'login'])->name('login');
                Route::put('clear-favs', [ProfileController::class, 'clearFavs'])->name('clear-favs');
            });

        });

    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('{post}', [PostController::class, 'show'])->name('show');
        Route::post('{post}/view', [PostController::class, 'view']);
    });

});
