<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MobileResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $email = urlencode($notifiable->email);

        $url = "mgmops://reset-password?token={$this->token}&email={$email}";

        return (new MailMessage)
            ->subject('Reset Your MGM Ops Password')
            ->greeting('Hello,')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password in App', $url)
            ->line('If you did not request this, you can safely ignore this email.');
    }
}