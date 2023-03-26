<?php

namespace App\Notifications;

use Closure;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class WebauthnRecoveryNotification extends Notification
{
    /**
     * Token for account recovery.
     */
    protected string $token;

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
     * AccountRecoveryNotification constructor.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

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
        // if (static::$toMailCallback) {
        //     return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        // }

        // if (static::$createUrlCallback) {
        //     $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        // } else {
        $url = url(
            route(
                'webauthn.recover',
                [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ],
                false
            )
        );
        // }

        return (new MailMessage)
            ->subject(Lang::get('Account Recovery Notification'))
            ->line(
                Lang::get(
                    'You are receiving this email because we received an account recovery request for your account.'
                )
            )
            ->action(Lang::get('Recover Account'), $url)
            ->line(
                Lang::get(
                    'This recovery link will expire in :count minutes.',
                    ['count' => config('auth.passwords.webauthn.expire')]
                )
            )
            ->line(Lang::get('If you did not request an account recovery, no further action is required.'));
    }

    // /**
    //  * Set a callback that should be used when creating the reset password button URL.
    //  *
    //  * @param  \Closure|null  $callback
    //  *
    //  * @return void
    //  */
    // public static function createUrlUsing(?Closure $callback): void
    // {
    //     static::$createUrlCallback = $callback;
    // }

    // /**
    //  * Set a callback that should be used when building the notification mail message.
    //  *
    //  * @param  \Closure|null  $callback
    //  *
    //  * @return void
    //  */
    // public static function toMailUsing(?Closure $callback): void
    // {
    //     static::$toMailCallback = $callback;
    // }
}
