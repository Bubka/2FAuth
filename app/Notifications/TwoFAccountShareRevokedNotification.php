<?php

namespace App\Notifications;

use App\Models\TwoFAccount;
use App\Notifications\Traits\RendersTwoFAccount;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFAccountShareRevokedNotification extends Notification
{
    use RendersTwoFAccount;

    protected TwoFAccount $twofaccount;

    protected string $actorName;

    protected bool $isAllUsersScope;

    /**
     * TwoFAccountShareRevokedNotification constructor.
     */
    public function __construct(TwoFAccount $twofaccount, string $actorName, bool $isAllUsersScope)
    {
        $this->twofaccount     = $twofaccount;
        $this->actorName       = $actorName;
        $this->isAllUsersScope = $isAllUsersScope;
    }

    /**
     * Get the notification's channels.
     *
     * @return array<int, string>
     */
    public function via(mixed $notifiable) : array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(mixed $notifiable) : MailMessage
    {
        $resumeKey = $this->isAllUsersScope
            ? 'message.notifications.twofaccount_share_revoked.resume_all_users'
            : 'message.notifications.twofaccount_share_revoked.resume_user';

        return (new MailMessage)
            ->subject(__('message.notifications.twofaccount_share_revoked.subject'))
            ->greeting(__('message.notifications.hello_user', ['username' => $notifiable->name]))
            ->line(__($resumeKey, [
                'actor'       => $this->actorName,
                'twofaccount' => $this->twoFAccountLabel(),
            ]))
            ->line(__('message.notifications.regards'))
            ->action(__('link.go_to_2fauth_host'), url('/'))
            ->line(__('message.notifications.regards'));
    }
}
