<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement; // Modelleri import et
use App\Models\Conversation; // Modelleri import et

class NavbarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 'layouts.navigation' view'ı her yüklendiğinde bu fonksiyonu çalıştır
        View::composer('layouts.navigation', function ($view) {
            // Sadece kullanıcı giriş yapmışsa bu işlemleri yap
            if (Auth::check()) {
                $user = Auth::user();

                // OKUNMAMIŞ DUYURULARI HESAPLAMA MANTIĞI
                // (Bu mantık için 'announcement_user' adında bir "okundu" pivot tablosu gerekir)
                // Şimdilik sahte bir sayı kullanalım.
                $unreadAnnouncementsCount = 2;

                // OKUNMAMIŞ MESAJLARI HESAPLAMA MANTIĞI
                // Kullanıcının dahil olduğu ve en son mesajı kendisinin göndermediği
                // ve 'read_at' sütunu boş olan konuşmaları say.
                $unreadMessagesCount = $user->conversations()
                    ->where(function ($query) use ($user) {
                        $query->whereHas('messages', function ($subQuery) use ($user) {
                            // En son mesajın ID'sinin, kullanıcının göndermediği bir mesaj olmasını sağla
                            $subQuery->where('user_id', '!=', $user->id);
                        });
                        // ve bu konuşmanın okunmamış olarak işaretlendiğini kontrol et (read_at pivot sütunu)
                        $query->whereNull('conversation_user.read_at');
                    })
                    ->count();

                // Hesaplanan verileri view'a gönder
                $view->with('unreadAnnouncementsCount', $unreadAnnouncementsCount)
                    ->with('unreadMessagesCount', $unreadMessagesCount);
            }
        });
    }
}
