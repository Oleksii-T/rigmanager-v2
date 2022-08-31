<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;

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

Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('index');
})->name('logout');

Route::middleware(['localeSessionRedirect', 'localizationRedirect'])->prefix(LaravelLocalization::setLocale())->group(function () {

    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('terms', [PageController::class, 'terms'])->name('terms');
    Route::get('privacy', [PageController::class, 'privacy'])->name('privacy');

    Route::get('search', [SearchController::class, 'index'])->name('search');

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('{category}', [SearchController::class, 'category'])->name('show');
    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('{post}', [PostController::class, 'show'])->name('show');
        Route::post('{post}/add-to-fav', [PostController::class, 'addToFav'])->name('add-to-fav');
    });

    Route::middleware('verified')->group(function () {

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('password', [ProfileController::class, 'password'])->name('password');
        });

    });

});
