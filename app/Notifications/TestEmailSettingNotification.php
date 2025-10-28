<?php

namespace App\Notifications;

use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class TestEmailSettingNotification extends Notification
{
    // /**
    //  * The callback that should be used to create the reset password URL.
    //  *
    //  * @var \Closure|null
    //  */
    // protected static ?Closure $createUrlCallback;

    // /**
    //  * The callback that should be used to build the mail message.
    //  *
    //  * @var \Closure|null
    //  */
    // protected static ?Closure $toMailCallback;

    /**
     * TestEmailSettingNotification constructor.
     */
    public function __construct() {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('message.notifications.test_email_settings.subject'))
            ->greeting(Lang::get('message.notifications.hello'))
            ->line(
                Lang::get('message.notifications.test_email_settings.reason')
            )
            ->line(
                Lang::get('message.notifications.test_email_settings.success')
            );
    }
}
