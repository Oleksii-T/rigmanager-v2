<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ExchangeRateController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MailerController;
use App\Http\Controllers\Admin\MailerLogController;

/*
 *
 * Routes for admin panel
 *
 */

Route::middleware('is-admin')->group(function () {

    Route::get('/', function() {
        return redirect()->route('admin.index');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('index');
    Route::prefix('charts')->name('charts.')->group(function () {
        Route::get('users-created', [DashboardController::class, 'usersCreated'])->name('users-created');
        Route::get('posts-created', [DashboardController::class, 'postsCreated'])->name('posts-created');
        Route::get('models-created', [DashboardController::class, 'modelsCreated'])->name('models-created');
    });

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('categories', CategoryController::class);

    Route::resource('posts', PostController::class);

    Route::resource('mailer-logs', MailerLogController::class)->only('index');

    Route::resource('mailers', MailerController::class)->except('show');

    Route::resource('partners', PartnerController::class)->except('show');

    Route::resource('imports', ImportController::class)->only('index', 'show');

    Route::resource('users', UserController::class);

    Route::resource('faqs', FaqController::class)->except('show');

    Route::resource('feedbacks', FeedbackController::class)->only('show', 'index', 'destroy', 'update');

    Route::resource('blogs', BlogController::class)->except('show');

    Route::post('exchange-rates/sync-currencies', [ExchangeRateController::class, 'syncCurrencies'])->name('exchange-rates.sync-currencies');
    Route::post('exchange-rates/sync-rates', [ExchangeRateController::class, 'syncRates'])->name('exchange-rates.sync-rates');
    Route::resource('exchange-rates', ExchangeRateController::class)->only('index', 'edit', 'update', 'create');

    Route::resource('attachments', AttachmentController::class)->only('index', 'edit', 'update', 'destroy');
});
