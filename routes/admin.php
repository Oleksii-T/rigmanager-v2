<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\ExchangeRateController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\MailerController;

/*
 *
 * Routes for admin panel
 *
 */

Route::middleware('is-admin')->group(function () {

    Route::get('/', function() {
        return redirect()->route('admin.index');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('categories', CategoryController::class);

    Route::resource('posts', PostController::class);

    Route::resource('mailers', MailerController::class)->except('show');

    Route::resource('partners', PartnerController::class)->except('show');

    Route::resource('users', UserController::class);

    Route::post('exchange-rates/sync', [ExchangeRateController::class, 'sync'])->name('exchange-rates.sync');
    Route::resource('exchange-rates', ExchangeRateController::class)->only('index', 'edit', 'update', 'create');

    Route::resource('attachments', AttachmentController::class)->only('index', 'edit', 'update', 'destroy');
});
