<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Site;
use App\Models\Block;
use App\Models\Unit;
use App\Models\Apartment; // Bu satırı da ekleyelim, eski bir referans kalmasın
use App\Models\FeeTemplate;
use App\Models\Announcement;
use App\Models\Fixture;
use App\Policies\UserPolicy;
use App\Policies\SitePolicy;
use App\Policies\BlockPolicy;
use App\Policies\UnitPolicy;
use App\Policies\ApartmentPolicy;
use App\Policies\FeeTemplatePolicy;
use App\Policies\AnnouncementPolicy;
use App\Policies\FixturePolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Site::class => SitePolicy::class,
        Block::class => BlockPolicy::class,
        Unit::class => UnitPolicy::class,
        Apartment::class => ApartmentPolicy::class,
        Fee::class => FeePolicy::class,
        FeeTemplate::class => FeeTemplatePolicy::class,
        Announcement::class => AnnouncementPolicy::class,
        Conversation::class => ConversationPolicy::class,
        //Fixture::class => FixturePolicy::class,
        Fixture::class => FixturePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();


        // "God Mode" kuralı: 'super-admin' rolündeki kullanıcı
        // tüm yetki kontrollerinden otomatik olarak geçer.
        //Gate::before(fn ($user, $ability) => $user->hasRole('super-admin') ? true : null);
        //Gate::before(function ($user, $ability) {if ($user->hasRole('super-admin')) {return true; } });
        //Gate::before(fn ($user, $ability) => $user->hasRole('super-admin') ? true : null);


        Gate::before(function ($user, $ability) {
            if ($user && $user->hasRole('super-admin')) {
                return true;
            }
        });


    }
}
