<?php

namespace App\Notifications;

use App\Models\TwoFAccount;
use App\Notifications\Traits\RendersTwoFAccount;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFAccountSharedNotification extends Notification
{
    use RendersTwoFAccount;

    protected TwoFAccount $twofaccount;

    protected string $actorName;

    protected bool $isAllUsersScope;

    /**
     * TwoFAccountSharedNotification constructor.
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
            ? 'message.notifications.twofaccount_shared.resume_all_users'
            : 'message.notifications.twofaccount_shared.resume_user';

        return (new MailMessage)
            ->subject(__('message.notifications.twofaccount_shared.subject'))
            ->greeting(__('message.notifications.hello_user', ['username' => $notifiable->name]))
            ->line(__($resumeKey, [
                'actor'       => $this->actorName,
                'twofaccount' => $this->twoFAccountLabel(),
            ]))
            ->line(__('message.notifications.twofaccount_shared.use_it_to_generate_otp'))
            ->action(__('link.go_to_2fauth_host'), url('/'));
    }
}
