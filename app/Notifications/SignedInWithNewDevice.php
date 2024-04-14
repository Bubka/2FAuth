<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Jenssegers\Agent\Agent;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

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

    public function __construct(AuthenticationLog $authenticationLog)
    {
        $this->authenticationLog = $authenticationLog;
        $this->agent             = new Agent();
        $this->agent->setUserAgent($authenticationLog->user_agent);
    }

    public function via($notifiable)
    {
        return $notifiable->notifyAuthenticationLogVia();
    }

    public function toMail($notifiable)
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
