<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminToUserCommunication extends Notification
{
    use Queueable;

    private $message;
    private $subject;

    /**
     * Create a new notification instance.
     */
    public function __construct($subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }



    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Hangi kanallardan gönderileceğini burada belirtiriz.
        // .env dosyasında SMS ve Mail ayarlarınızın yapıldığını varsayıyoruz.
        return ['mail', 'database']; // 'vonage' (SMS için) eklenebilir.
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->message)
            ->line('Binsiyon sisteminden gönderilmiştir.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Veritabanına kaydedilecek format (uygulama içi bildirim)
        return [
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
