<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class FixtureMaintenanceDue extends Notification
{
    use Queueable;

    public Collection $fixtures;
    public Site $site;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $fixtures, Site $site)
    {
        $this->fixtures = $fixtures;
        $this->site = $site;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // Şimdilik sadece e-posta
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->site->name . ' Sitesi İçin Bakım Hatırlatması')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line($this->site->name . ' sitesinde bakımı yaklaşan veya geçmiş demirbaşlar bulunmaktadır.')
            ->view('emails.fixtures.maintenance_due', [
                'fixtures' => $this->fixtures,
                'site' => $this->site
            ]);
    }
}
