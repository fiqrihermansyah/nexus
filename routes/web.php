<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobScheduleController;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::get('/login',  [App\Http\Controllers\Auth\LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout',[App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Memo routes
    Route::get('/memo/export',                   [MemoController::class, 'export'])->name('memo.export');
    Route::get('/memo/{memo}/attachment',         [MemoController::class, 'previewAttachment'])->name('memo.attachment.preview');
    Route::get('/memo/{memo}/attachment/download',[MemoController::class, 'downloadAttachment'])->name('memo.attachment.download');
    Route::resource('memo', MemoController::class);

    // Job Schedule routes
    Route::get('/job-schedule/export', [JobScheduleController::class, 'export'])->name('job-schedule.export');
    Route::resource('job-schedule', JobScheduleController::class);

    // User management (admin only)
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
});
