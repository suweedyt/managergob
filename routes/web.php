<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\PostController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Auth\TramiteSettingController;
use App\Http\Controllers\Auth\TramiteController;
use App\Http\Controllers\Auth\CategoryController;
use App\Http\Controllers\Auth\FeatureSettingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(WebsiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/news',  'news')->name('news');
    Route::get('/news/single/{new}',  'show')->name('news.single');
    Route::get('/tramites', 'tramites')->name('tramites');
    Route::get('/tramites/{tramite}', 'tramiteShow')->name('tramites.show');
});

Auth::routes();

Route::prefix('auth')->middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('auth.dashboard');

    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::resource('tramites', TramiteController::class);
    Route::resource('tramitessettings', TramiteSettingController::class);
    Route::resource('featuresettings', FeatureSettingController::class)->only(['index', 'store']);

    Route::get('posts/{post}/preview', [PostController::class, 'preview'])->name('posts.preview');
    Route::resource('site-settings', \App\Http\Controllers\Auth\SiteSettingController::class)->only(['index', 'store']);
});
