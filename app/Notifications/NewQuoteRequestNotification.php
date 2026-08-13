<?php

namespace App\Notifications;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewQuoteRequestNotification extends Notification implements ShouldQueue
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
        $item = $this->quote->items->first();
        $productLine = $item
            ? "{$item->product_name} — {$item->quantity} {$item->unit}"
            : 'No product line attached';

        return (new MailMessage)
            ->subject("New quotation request {$this->quote->reference}")
            ->greeting('New quotation request')
            ->line("Reference: {$this->quote->reference}")
            ->line("Customer: {$this->quote->name}".($this->quote->company ? " ({$this->quote->company})" : ''))
            ->line("Email: {$this->quote->email}")
            ->line("Phone: {$this->quote->phone}")
            ->line("Product: {$productLine}")
            ->action('Open in admin', route('admin.quotes.show', $this->quote))
            ->line('Please review and respond from the admin quotation inbox.');
    }
}
