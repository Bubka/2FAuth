<?php

namespace App\Models;

use App\Models\Traits\WebAuthnManageCredentials;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laragear\WebAuthn\WebAuthnAuthentication;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements WebAuthnAuthenticatable
{
    use WebAuthnAuthentication, WebAuthnManageCredentials;
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
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'is_admin'           => 'boolean',
        'twofaccounts_count' => 'integer',
        'groups_count'       => 'integer',
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

        Log::info(sprintf('Password reset token sent to user id "%s', $this->id));
    }

    /**
     * Get Preferences attribute
     *
     * @param  string  $value
     * @return \Illuminate\Support\Collection<array-key, mixed>
     */
    public function getPreferencesAttribute($value)
    {
        $preferences = collect(config('2fauth.preferences'))->merge(json_decode($value));  /** @phpstan-ignore-line */

        return $preferences;
    }

    /**
     * set Email attribute
     *
     * @param  string  $value
     */
    public function setEmailAttribute($value) : void
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Returns an WebAuthnAuthenticatable user from a given Credential ID.
     *
     * @param  string  $id
     * @return WebAuthnAuthenticatable|null
     */
    public static function getFromCredentialId(string $id) : ?WebAuthnAuthenticatable
    {
        return static::whereHas(
            'webauthnCredentials',
            static function ($query) use ($id) {
                return $query->whereKey($id);
            }
        )->first();
    }

    /**
     * Get the TwoFAccounts of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TwoFAccount>
     */
    public function twofaccounts()
    {
        return $this->hasMany(\App\Models\TwoFAccount::class);
    }

    /**
     * Get the Groups of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Group>
     */
    public function groups()
    {
        return $this->hasMany(\App\Models\Group::class);
    }
}
