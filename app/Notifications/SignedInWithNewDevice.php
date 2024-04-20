<?php

namespace App\Notifications;

use Bubka\LaravelAuthenticationLog\Models\AuthenticationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;

class SignedInWithNewDevice extends Notification implements ShouldQueue
{
    use Queueable;

    public AuthenticationLog $authenticationLog;

    /**
     * A user agent parser instance.
     *
     * @var mixed
     */
    protected $agent;

    /**
     * Create a new SignedInWithNewDevice instance
     */
    public function __construct(AuthenticationLog $authenticationLog)
    {
        $this->authenticationLog = $authenticationLog;
        $this->agent             = new Agent();
        $this->agent->setUserAgent($authenticationLog->user_agent);
    }

    public function via(mixed $notifiable) : array|string
    {
        return $notifiable->notifyAuthenticationLogVia();
    }

    /**
     * Wrap the notification to a mail envelop
     */
    public function toMail(mixed $notifiable) : MailMessage
    {
        return (new MailMessage())
            ->subject(__('notifications.new_device.subject'))
            ->markdown('emails.newDevice', [
                'account'   => $notifiable,
                'time'      => $this->authenticationLog->login_at,
                'ipAddress' => $this->authenticationLog->ip_address,
                'browser'   => $this->agent->browser(),
                'platform'  => $this->agent->platform(),
            ]);
    }
}
