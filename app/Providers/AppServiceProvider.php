<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\NavLink;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Unread-message count for the admin sidebar badge.
        View::composer('layouts.admin', function ($view) {
            try {
                $view->with('unreadCount', ContactMessage::where('read', false)->count());
            } catch (Throwable $e) {
                // Never let a sidebar badge take down the admin panel.
                $view->with('unreadCount', 0);
            }
        });

        // Header, footer and meta tags are all admin-editable, so the public
        // layout needs settings and link tables on every render — including on
        // pages whose controller never touched them.
        View::composer('layouts.app', function ($view) {
            try {
                $view->with([
                    'settings'    => Setting::getAllSettings(),
                    'navLinks'    => NavLink::live(),
                    'socialLinks' => SocialLink::live(),
                ]);
            } catch (Throwable $e) {
                $view->with([
                    'settings'    => [],
                    'navLinks'    => collect(),
                    'socialLinks' => collect(),
                ]);
            }
        });


        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
