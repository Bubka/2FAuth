<?php

namespace App\Notifications;

use App\Models\AuthLog;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;

class SignedInWithNewDeviceNotification extends Notification
{
    /**
     * The AuthLog model instance
     */
    public AuthLog $authLog;

    /**
     * A user agent parser instance.
     *
     * @var mixed
     */
    protected $agent;

    /**
     * Create a new SignedInWithNewDeviceNotification instance
     */
    public function __construct(AuthLog $authLog)
    {
        $this->authLog = $authLog;
        $this->agent   = new Agent;
        $this->agent->setUserAgent($authLog->user_agent);
    }

    public function via(mixed $notifiable) : array|string
    {
        return $notifiable->notifyAuthLogVia();
    }

    /**
     * Wrap the notification to a mail envelop
     */
    public function toMail(mixed $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject(__('message.notifications.new_device.subject'))
            ->markdown('emails.signedInWithNewDevice', [
                'account'   => $notifiable,
                'time'      => $this->authLog->login_at,
                'ipAddress' => $this->authLog->ip_address,
                'browser'   => $this->agent->browser(),
                'platform'  => $this->agent->platform(),
            ]);
    }
}
