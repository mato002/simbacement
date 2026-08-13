<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public JobApplication $application) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $role = $this->application->jobListing?->title ?: ($this->application->position ?: 'the role');

        return (new MailMessage)
            ->subject('Application received — Simba Cement Careers')
            ->greeting("Hello {$this->application->full_name},")
            ->line("Thank you for applying for {$role} at Simba Cement.")
            ->line('We have received your application and CV. Our HR team will review your submission and contact you if there is a match.')
            ->action('View open roles', route('careers.index'));
    }
}
