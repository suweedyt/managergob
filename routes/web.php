<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\PostController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(WebsiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/news',  'news')->name('news');
    Route::get('/news/single/{new}',  'show')->name('news.single');
});

Auth::routes();

Route::prefix('auth')->middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('auth.dashboard');
    Route::resource('posts', PostController::class);
    // Preview a post as it would appear on the public site (AJAX modal in admin)
    Route::get('posts/{post}/preview', [PostController::class, 'preview'])->name('posts.preview');
});
