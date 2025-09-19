<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\ResidentDashboardComposer;
use App\Models\Announcement;
use App\Models\Message;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(['layouts.navigation', 'layouts.partials.sidebar'], function ($view) {

            // Sadece bir kullanıcı giriş yapmışsa bu verileri çekmeye çalış.
            if (Auth::check()) {
                $user = Auth::user();

                // Üst bar için bildirim sayıları
                $unreadAnnouncementsCount = Announcement::whereDoesntHave('reads', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->count();

                $unreadMessagesCount = Message::where('to_user_id', $user->id)->whereNull('read_at')->count();

                // Site/Blok seçimi dropdown menüleri için veri listeleri
                $managedSites = $user->managedSites;
                $managedBlocks = $user->managedBlocks;

                // Çekilen tüm verileri view'a gönder.
                $view->with([
                    'unreadAnnouncementsCount' => $unreadAnnouncementsCount,
                    'unreadMessagesCount' => $unreadMessagesCount,
                    'managedSites' => $managedSites,
                    'managedBlocks' => $managedBlocks,
                ]);


                $latestAnnouncements = Announcement::latest()->take(3)->get();
                $latestMessages = Message::where('to_user_id', $user->id)->latest()->take(3)->get();

                $view->with([
                    'unreadAnnouncementsCount' => $unreadAnnouncementsCount,
                    'unreadMessagesCount' => $unreadMessagesCount,
                    'managedSites' => $managedSites,
                    'managedBlocks' => $managedBlocks,
                    'latestAnnouncements' => $latestAnnouncements, // Yeni veriyi view'a gönder
                    'latestMessages' => $latestMessages,      // Yeni veriyi view'a gönder
                ]);
            }
        });
    }
}
