<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttachmentController;

/*
 *
 * Routes for admin panel
 *
 */

Route::get('/login', function() {
    if (auth()->check()) {
        return redirect()->route('admin.index');
    }
    return view('admin.auth.login');
})->name('login');

Route::middleware('is-admin')->group(function () {

    Route::get('/', function() {
        return redirect()->route('admin.index');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('categories', CategoryController::class);

    Route::resource('posts', PostController::class);

    Route::resource('users', UserController::class);

    Route::prefix('attachments')->name('attachments.')->group(function () {
        Route::get('{attachment}/download', [AttachmentController::class, 'download'])->name('download');
		Route::delete('{attachment}', [AttachmentController::class, 'destroy'])->name('destroy');
	});
});
