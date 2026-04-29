<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public Application $application) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $job    = $this->application->job;
        $status = $this->application->status;
        $reason = $this->application->rejection_reason;

        $messages = [
            'Accepted' => "Congratulations! Your application for \"{$job->title}\" has been accepted.",
            'Rejected' => "Your application for \"{$job->title}\" was not selected at this time."
                         . ($reason ? " Reason: {$reason}" : ''),
            'Pending'  => "Your application for \"{$job->title}\" is under review.",
        ];

        return [
            'application_id'   => $this->application->id,
            'job_id'           => $job->id,
            'job_title'        => $job->title,
            'status'           => $status,
            'rejection_reason' => $reason,
            'message'          => $messages[$status] ?? "Your application status has been updated to {$status}.",
        ];
    }
}
