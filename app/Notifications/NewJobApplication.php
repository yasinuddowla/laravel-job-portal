<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplication extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Application for: ' . $this->application->job->title)
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new application for the job: ' . $this->application->job->title)
            ->line('Applicant: ' . $this->application->name)
            ->line('Email: ' . $this->application->email)
            ->action('View Application', url('/admin/applications/' . $this->application->id))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title,
            'applicant_name' => $this->application->name,
            'applicant_email' => $this->application->email,
        ];
    }
}
