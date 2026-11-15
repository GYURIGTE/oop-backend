<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the reset email with a link that points at the React frontend.
     */
    public function toMail($notifiable): MailMessage
    {
        $frontend = rtrim((string) config('app.frontend_url'), '/');

        $url = $frontend.'/auth/reset-password?'.http_build_query([
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $minutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Reset your BALIGYA password')
            ->greeting('Hello '.$notifiable->full_name.'!')
            ->line('We received a request to reset the password for your BALIGYA account.')
            ->action('Reset Password', $url)
            ->line('This link will expire in '.$minutes.' minutes.')
            ->line('If you did not request a password reset, you can safely ignore this email.');
    }
}
