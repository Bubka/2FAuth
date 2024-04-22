<?php

namespace App\Notifications;

use App\Models\AuthenticationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FailedLogin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The AuthenticationLog model instance
     */
    public AuthenticationLog $authenticationLog;

    public function __construct(AuthenticationLog $authenticationLog)
    {
        $this->authenticationLog = $authenticationLog;
    }

    /**
     * Get the notification's channels.
     */
    public function via(mixed $notifiable) : array|string
    {
        return $notifiable->notifyAuthenticationLogVia();
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable) : MailMessage
    {
        return (new MailMessage())
            ->subject(__('A failed login to your account'))
            ->markdown('authentication-log::emails.failed', [
                'account'   => $notifiable,
                'time'      => $this->authenticationLog->login_at,
                'ipAddress' => $this->authenticationLog->ip_address,
                'browser'   => $this->authenticationLog->user_agent,
                'location'  => $this->authenticationLog->location,
            ]);
    }
}
