<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;



class UserConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * User modelinin örneği.
     * Bu özelliğin "public" olması, Blade şablonunda otomatik olarak
     * $user değişkenine erişilmesini sağlar.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Yeni bir mesaj örneği oluştur.
     *
     * @param \App\Models\User $user
     * @return void
     */
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Binsiyon Hesabınızı Onaylayın',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmation', // Bu view'ı birazdan oluşturacağız

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
