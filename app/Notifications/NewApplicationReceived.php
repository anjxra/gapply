<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewApplicationReceived extends Notification
{
    use Queueable;

    public function __construct(public Application $application) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $applicant = $this->application->applicant;
        $job       = $this->application->job;

        return [
            'application_id' => $this->application->id,
            'job_id'         => $job->id,
            'job_title'      => $job->title,
            'applicant_id'   => $applicant->id,
            'applicant_name' => $applicant->name,
            'applicant_email'=> $applicant->email,
            'message'        => "{$applicant->name} applied for \"{$job->title}\".",
            'type'           => 'new_application',
        ];
    }
}
