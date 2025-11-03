<?php

namespace App\Notifications;

use App\Models\AuthLog;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;

class FailedLoginNotification extends Notification
{
    /**
     * A user agent parser instance.
     *
     * @var mixed
     */
    protected $agent;

    /**
     * The AuthLog model instance
     */
    public AuthLog $authLog;

    /**
     * Create a new FailedLoginNotification instance
     */
    public function __construct(AuthLog $authLog)
    {
        $this->authLog = $authLog;
        $this->agent   = new Agent;
        $this->agent->setUserAgent($authLog->user_agent);
    }

    /**
     * Get the notification's channels.
     */
    public function via(mixed $notifiable) : array|string
    {
        return $notifiable->notifyAuthLogVia();
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject(__('message.notifications.failed_login.subject'))
            ->markdown('emails.failedLogin', [
                'account'   => $notifiable,
                'time'      => $this->authLog->login_at,
                'ipAddress' => $this->authLog->ip_address,
                'browser'   => $this->agent->browser(),
                'platform'  => $this->agent->platform(),
            ]);
    }
}
