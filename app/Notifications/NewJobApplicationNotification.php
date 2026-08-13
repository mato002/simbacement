<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplicationNotification extends Notification implements ShouldQueue
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
        $role = $this->application->jobListing?->title ?: ($this->application->position ?: 'Open role');

        return (new MailMessage)
            ->subject("New job application: {$this->application->full_name}")
            ->greeting('New career application')
            ->line("Applicant: {$this->application->full_name}")
            ->line("Email: {$this->application->email}")
            ->line("Phone: {$this->application->phone}")
            ->line("Role: {$role}")
            ->action('Open in admin', route('admin.applications.show', $this->application))
            ->line('CV is available for download from the admin panel (private storage).');
    }
}
