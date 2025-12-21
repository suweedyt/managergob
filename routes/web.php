<?php

use App\Http\Controllers\Auth\AdminSettingController;
use App\Http\Controllers\Auth\BannerController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\CategoryController;
use App\Http\Controllers\Auth\FeatureSettingController;
use App\Http\Controllers\Auth\PostController;
use App\Http\Controllers\Auth\SiteSettingController;
use App\Http\Controllers\Auth\TramiteController;
use App\Http\Controllers\Auth\TramiteSettingController;
use App\Http\Controllers\Auth\LocationController;
use App\Http\Controllers\Auth\ContactSettingController;
use App\Http\Controllers\Auth\SectionController;
use App\Http\Controllers\Auth\SectionSettingController;
use App\Http\Controllers\Auth\NewsSliderSettingController;
use App\Http\Controllers\Auth\NewsShowcaseController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::controller(WebsiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/news',  'news')->name('news');
    Route::get('/news/single/{new}',  'show')->name('news.single');
    Route::get('/tramites', 'tramites')->name('tramites');
    Route::get('/tramites/{tramite}/link', 'tramiteLink')->name('tramites.link');
    Route::get('/tramites/{tramite}', 'tramiteShow')->name('website.tramites.show');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/sections', 'sections')->name('sections');
    Route::get('/destacado/{slug?}', 'featureLanding')->name('feature.landing');
});

Auth::routes();

Route::prefix('auth')->middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('auth.dashboard');

    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('tramites', TramiteController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('sectionssettings', SectionSettingController::class)->only(['index', 'store']);
    // Preview route for banners (opens standalone preview page) - must be before resource routes
    Route::get('banners/preview', [\App\Http\Controllers\Auth\BannerPreviewController::class, 'show'])->name('banners.preview');
    Route::resource('banners', BannerController::class);

    Route::resource('tramitessettings', TramiteSettingController::class);
    Route::resource('contactsettings', ContactSettingController::class)->only(['index', 'store']);
    Route::resource('featuresettings', FeatureSettingController::class)->only(['index', 'store']);
    Route::resource('admin-settings', AdminSettingController::class)->only(['index', 'store']);
    Route::resource('newsslider', NewsSliderSettingController::class)->only(['index', 'store']);
    Route::resource('newsshowcase', NewsShowcaseController::class)->only(['index', 'store']);

    Route::get('posts/{post}/preview', [PostController::class, 'preview'])->name('posts.preview');
    Route::resource('site-settings', SiteSettingController::class)->only(['index', 'store']);

    Route::post('newsshowcase/{id}/toggle-size', [NewsShowcaseController::class, 'toggleSize'])
        ->name('newsshowcase.toggleSize');
});