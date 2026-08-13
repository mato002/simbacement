<?php

namespace App\Notifications;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteRequestConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public QuoteRequest $quote) {}

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
            ->subject("We received your quotation request {$this->quote->reference}")
            ->greeting("Hello {$this->quote->name},")
            ->line('Thank you for contacting Simba Cement. Your quotation request has been received.')
            ->line("Reference number: {$this->quote->reference}")
            ->line('Our sales team will review your requirements and follow up shortly.')
            ->action('Visit website', route('home'))
            ->line('If you need to add details, reply to this email or contact our sales team.');
    }
}
