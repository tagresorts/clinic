<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TreatmentPlanAppointmentReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        $template = EmailTemplate::where('type', 'treatment_plan_appointment_reminder')->first();

        $body = str_replace(
            ['{patient_name}', '{appointment_date}', '{appointment_time}'],
            [$this->appointment->patient->full_name, $this->appointment->appointment_datetime->format('F d, Y'), $this->appointment->appointment_datetime->format('g:i A')],
            $template->body
        );

        return (new MailMessage)
            ->subject($template->subject)
            ->line($body);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
