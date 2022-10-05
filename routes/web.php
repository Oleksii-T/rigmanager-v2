<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\MailerController;

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

Route::get('auth/social/{provider}', [SocialAuthController::class, 'redirect'])->name('auth.social');
Route::get('auth/callback/{provider}', [SocialAuthController::class, 'callback']);
Route::get('catalog', [PageController::class, 'categories'])->name('categories');
Route::get('contact-us', [PageController::class, 'contact-us'])->name('contact-us');

Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('index');
})->name('logout');

Route::middleware(['localeSessionRedirect', 'localizationRedirect'])->prefix(LaravelLocalization::setLocale())->group(function () {

    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('terms', [PageController::class, 'terms'])->name('terms');
    Route::get('privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('categories', [PageController::class, 'categories'])->name('categories');
    Route::get('catalog', [SearchController::class, 'index'])->name('search');

    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::put('posts/{post}/view', [PostController::class, 'view']);

    Route::middleware('auth')->group(function () {

        Route::middleware('verified')->group(function () {

            Route::prefix('posts')->name('posts.')->group(function () {
                Route::get('create', [PostController::class, 'create'])->name('create');
                Route::get('{post}/edit', [PostController::class, 'edit'])->name('edit');
                Route::get('{post}/contacts', [PostController::class, 'contacts']);
                Route::get('{post}/views', [PostController::class, 'views'])->name('views');
                Route::post('', [PostController::class, 'store'])->name('store');
                Route::put('{post}/add-to-fav', [PostController::class, 'addToFav'])->name('add-to-fav');
                Route::put('{post}/toggle-active', [PostController::class, 'toggle'])->name('toggle');
                Route::put('{post}', [PostController::class, 'update'])->name('update');
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
                Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
                Route::get('posts/{category?}', [ProfileController::class, 'posts'])->name('posts');
                Route::get('favorites/{category?}', [ProfileController::class, 'favorites'])->name('favorites');
                Route::post('posts/action', [ProfileController::class, 'action']);
                Route::put('/', [ProfileController::class, 'update'])->name('update');
                Route::put('password', [ProfileController::class, 'password'])->name('password');
                Route::put('clear-favs', [ProfileController::class, 'clearFavs'])->name('clear-favs');
            });

        });

    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('{post}', [PostController::class, 'show'])->name('show');
        Route::post('{post}/view', [PostController::class, 'view']);
    });

    Route::get('catalog/{category}', [SearchController::class, 'category'])->name('search.category');

});
