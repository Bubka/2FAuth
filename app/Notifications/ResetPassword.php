<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('message.notifications.reset_password.subject'))
            ->line(Lang::get('message.notifications.reset_password.reason'))
            ->action(Lang::get('label.reset_password'), $url)
            ->line(Lang::get('message.notifications.reset_password.expire_in_x_minutes', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('message.notifications.reset_password.no_action_required'));
    }
}
