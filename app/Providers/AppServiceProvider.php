<?php

namespace App\Providers;

use App\Models\Site;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement; // Varsayımsal model, bkz. Madde 3
use App\Models\Message; // Varsayımsal model
use App\Models\Fixture;
use App\Policies\FixturePolicy;
use App\Models\User;
use App\Observers\UserObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                // Okunmamış duyuru sayısını hesapla (Madde 3'teki yapıya göre)
                $unreadAnnouncementsCount = Announcement::whereDoesntHave('reads', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count();

                // Okunmamış mesaj sayısını hesapla (kendi mesaj modelinize göre)
                $unreadMessagesCount = Message::where('to_user_id', $user->id)->whereNull('read_at')->count();

                $view->with('unreadAnnouncementsCount', $unreadAnnouncementsCount);
                $view->with('unreadMessagesCount', $unreadMessagesCount);
            }

            View::composer(['layouts.navigation', 'layouts.app'], function ($view) {
                $managedSites = collect();
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user->hasRole('super-admin')) {
                        //$managedSites = Sites::all();
                        $managedSites= Site::all();
                    } else {
                        $managedSites = $user->sites;
                    }
                }
                $view->with('managedSites', $managedSites);
            });
        });
    }
}
