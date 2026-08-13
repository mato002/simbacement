<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification implements ShouldQueue
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
            ->subject("New contact message: {$this->message->subject}")
            ->greeting('New website contact message')
            ->line("From: {$this->message->name}")
            ->line("Email: {$this->message->email}")
            ->line('Department: '.ucfirst($this->message->department))
            ->line("Subject: {$this->message->subject}")
            ->line($this->message->message)
            ->action('Open in admin', route('admin.messages.show', $this->message))
            ->line('Respond from the admin messages inbox.');
    }
}
