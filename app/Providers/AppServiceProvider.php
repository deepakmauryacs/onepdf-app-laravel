<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;

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
        Schema::defaultStringLength(191);

        View::composer('vendor.layouts.partials.header', function ($view) {
            $notifications = collect();
            $unreadCount = 0;

            if ($user = Auth::user()) {
                $notifications = Notification::forUser($user->id)
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();

                $unreadCount = Notification::forUser($user->id)
                    ->where('is_read', false)
                    ->count();
            }

            $view->with([
                'notifications' => $notifications,
                'unreadCount'   => $unreadCount,
            ]);
        });
    }
}
