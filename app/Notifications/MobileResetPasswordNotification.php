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

    $url = url("/mobile-reset-password?token={$this->token}&email={$email}");

    return (new MailMessage)
        ->subject('Reset Your MGM Ops Password')
        ->greeting('Hello ' . ($notifiable->name ?? 'Team Member') . ',')
        ->line('We received a request to reset the password for your MGM Ops account.')
        ->line('Please tap the button below to reset your password.')
        ->action('Reset Password', $url)
        ->line('This reset link is valid for a limited time only.')
        ->line('If you did not request a password reset, no further action is required.')
        ->salutation('Regards, MGM Ops Team');
}
}