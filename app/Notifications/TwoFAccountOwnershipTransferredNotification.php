<?php

namespace App\Notifications;

use App\Models\TwoFAccount;
use App\Models\User;
use App\Notifications\Traits\RendersTwoFAccount;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFAccountOwnershipTransferredNotification extends Notification
{
    use RendersTwoFAccount;

    protected TwoFAccount $twofaccount;

    protected User $previousOwner;

    /**
     * TwoFAccountOwnershipTransferredNotification constructor.
     */
    public function __construct(TwoFAccount $twofaccount, User $previousOwner)
    {
        $this->twofaccount   = $twofaccount;
        $this->previousOwner = $previousOwner;
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
        return (new MailMessage)
            ->subject(__('message.notifications.twofaccount_ownership_transferred.subject'))
            ->greeting(__('message.notifications.hello_user', ['username' => $notifiable->name]))
            ->line(__('message.notifications.twofaccount_ownership_transferred.resume', [
                'actor'       => $this->previousOwner->name,
                'twofaccount' => $this->twoFAccountLabel(),
            ]))
            ->line(__('message.notifications.twofaccount_ownership_transferred.check_shares'))
            ->action(__('link.go_to_2fauth_host'), url('/'));
    }
}
