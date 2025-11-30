<?php

namespace App\Providers;

use App\Models\AdminSetting;
use App\Models\FeatureSetting;
use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    public function boot(): void
    {
        View::composer('layouts.website', function ($view) {
            $settings = SiteSetting::query()->first();

            if ($settings && !$settings->footer_socials) {
                $settings->footer_socials = [];
            }

            $view->with('siteSettings', $settings);
        });

        View::composer('layouts.auth', function ($view) {
            $features = FeatureSetting::query()->first();
            $siteSettings = SiteSetting::query()->first();
            $adminSettings = AdminSetting::query()->first();

            $view->with([
                'featureSettings' => $features,
                'siteSettings' => $siteSettings,
                'adminSettings' => $adminSettings,
            ]);
        });
    }
}
