<?php

namespace App\Providers;

use App\Models\AdminSetting;
use App\Models\FeatureSetting;
use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as AuthRedirectMiddleware;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    public function boot(): void
    {
        // Force https scheme for URL generation when in production or when explicitly required.
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

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

        // Ensure RedirectIfAuthenticated redirects to admin dashboard when user is authenticated
        AuthRedirectMiddleware::redirectUsing(function ($request) {
            // prefer the admin dashboard route if present
            if (method_exists($request->route(), 'getName') && $request->route()) {
                // always redirect authenticated users to the admin dashboard
            }

            if (app('router')->has('auth.dashboard')) {
                return route('auth.dashboard');
            }

            // fallback to existing behavior (home)
            return null;
        });
    }
}
