<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPasswordNotification
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $template = EmailTemplate::where('type', 'password_reset')->first();

        if (!$template) {
            return $this->buildDefaultMailMessage($notifiable);
        }

        $resetLink = $this->buildResetLink($notifiable);

        $body = str_replace(
            ['{{user_name}}', '{{reset_link}}'],
            [$notifiable->name, $resetLink],
            $template->body
        );

        $subject = str_replace(
            ['{{user_name}}'],
            [$notifiable->name],
            $template->subject
        );

        return (new MailMessage)
            ->subject($subject)
            ->line(' ') // Add an empty line to ensure proper rendering of HTML content
            ->view('emails.custom', ['body' => $body]);
    }

    /**
     * Build the default mail message if no template is found.
     */
    protected function buildDefaultMailMessage($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $this->buildResetLink($notifiable))
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Build the password reset link.
     */
    protected function buildResetLink($notifiable): string
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
