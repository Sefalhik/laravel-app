<?php

namespace App\Notifications;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterSentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Newsletter $newsletter,
        public int $count,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Newsletter envoyée : ' . $this->newsletter->subject)
            ->line('La newsletter "' . $this->newsletter->subject . '" a été envoyée avec succès.')
            ->line($this->count . ' abonné(s) ont été notifiés.');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
