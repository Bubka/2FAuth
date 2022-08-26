<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DarkGhostHunter\Larapass\Contracts\WebAuthnAuthenticatable;
use DarkGhostHunter\Larapass\WebAuthnAuthentication;
use DarkGhostHunter\Larapass\Notifications\AccountRecoveryNotification;

class User extends Authenticatable implements WebAuthnAuthenticatable
{
    use WebAuthnAuthentication;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
        
        Log::info('Password reset token sent');
    }

    /**
     * set Email attribute
     * @param string $value
     */
    public function setEmailAttribute($value) : void
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Sends a credential recovery email to the user.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendCredentialRecoveryNotification(string $token): void
    {
        $accountRecoveryNotification = new AccountRecoveryNotification($token);
        $accountRecoveryNotification->toMailUsing(null);

        $accountRecoveryNotification->createUrlUsing(function(mixed $notifiable, string $token) {
            $url = url(
                route(
                    'webauthn.recover',
                    [
                        'token' => $token,
                        'email' => $notifiable->getEmailForPasswordReset(),
                    ],
                    false
                )
            );

            return $url;
        });

        $this->notify($accountRecoveryNotification);
    }
}
