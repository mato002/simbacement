<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\QuoteRequest;
use App\Models\Setting;
use App\Notifications\ContactMessageConfirmationNotification;
use App\Notifications\JobApplicationConfirmationNotification;
use App\Notifications\NewContactMessageNotification;
use App\Notifications\NewJobApplicationNotification;
use App\Notifications\NewQuoteRequestNotification;
use App\Notifications\QuoteRequestConfirmationNotification;
use Illuminate\Support\Facades\Notification;

class LeadMailer
{
    public function quoteSubmitted(QuoteRequest $quote): void
    {
        $quote->loadMissing('items');

        $this->notifyAddress(
            $this->companyEmail('email_sales'),
            new NewQuoteRequestNotification($quote),
        );

        $this->notifyAddress(
            $quote->email,
            new QuoteRequestConfirmationNotification($quote),
        );
    }

    public function contactSubmitted(ContactMessage $message): void
    {
        $recipient = match ($message->department) {
            'sales' => $this->companyEmail('email_sales'),
            'support' => $this->companyEmail('email_support'),
            default => $this->companyEmail('email_support') ?: $this->companyEmail('email_sales'),
        };

        $this->notifyAddress($recipient, new NewContactMessageNotification($message));
        $this->notifyAddress($message->email, new ContactMessageConfirmationNotification($message));
    }

    public function applicationSubmitted(JobApplication $application): void
    {
        $application->loadMissing('jobListing');

        $this->notifyAddress(
            $this->companyEmail('email_careers'),
            new NewJobApplicationNotification($application),
        );

        $this->notifyAddress(
            $application->email,
            new JobApplicationConfirmationNotification($application),
        );
    }

    private function companyEmail(string $key): ?string
    {
        $email = Setting::getValue($key, null, 'company');

        if (filled($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $fallback = config('mail.from.address');

        return filled($fallback) && filter_var($fallback, FILTER_VALIDATE_EMAIL)
            ? $fallback
            : null;
    }

    private function notifyAddress(?string $email, object $notification): void
    {
        if (! filled($email)) {
            return;
        }

        Notification::route('mail', $email)->notify($notification);
    }
}
