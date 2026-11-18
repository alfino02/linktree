<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\LinktreePageController;
use Illuminate\Support\Facades\Route;

// Public Linktree Pages (Piknik Enthusiast & Lombok Enthusiast)
Route::get('/', [LinktreePageController::class, 'piknik'])->name('linktree.piknik');
Route::get('/piknik', [LinktreePageController::class, 'piknik']);
Route::get('/index.html', [LinktreePageController::class, 'piknik']);

Route::get('/lombok', [LinktreePageController::class, 'lombok'])->name('linktree.lombok');
Route::get('/lombok.html', [LinktreePageController::class, 'lombok']);

// Admin Studio Dashboard (manages both Piknik & Lombok)
Route::get('/admin', [LinktreePageController::class, 'admin'])->name('linktree.admin');
Route::get('/admin.html', [LinktreePageController::class, 'admin']);

// Backend JSON APIs (Content & Visitor Analytics)
Route::get('/api/content', [ContentController::class, 'show']);
Route::post('/api/content', [ContentController::class, 'handle']);
Route::post('/api/analytics', [AnalyticsController::class, 'handle']);
