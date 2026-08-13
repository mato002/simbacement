<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $message) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('We received your message — Simba Cement')
            ->greeting("Hello {$this->message->name},")
            ->line('Thank you for contacting Simba Cement. Your message has been received.')
            ->line("Subject: {$this->message->subject}")
            ->line('Our team will get back to you as soon as possible.')
            ->action('Visit website', route('home'));
    }
}
