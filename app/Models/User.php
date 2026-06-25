<?php

namespace App\Models;

use App\Facades\Settings;
use App\Models\Traits\HasAuthenticationLog;
use App\Models\Traits\WebAuthnManageCredentials;
use App\Notifications\ResetPassword;
use Database\Factories\UserFactory;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laragear\WebAuthn\Models\WebAuthnCredential;
use Laragear\WebAuthn\WebAuthnAuthentication;
use Laravel\Passport\Client;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $last_seen_at
 * @property bool $is_admin
 * @property Collection<array-key,mixed> $preferences
 * @property-read \Illuminate\Database\Eloquent\Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read int|null $groups_count
 * @property-read DatabaseNotificationCollection<array-key,DatabaseNotification>|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|TwoFAccount[] $twofaccounts
 * @property-read int|null $twofaccounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|WebAuthnCredential[] $webAuthnCredentials
 * @property-read int|null $web_authn_credentials_count
 * @property string|null $oauth_id
 * @property string|null $oauth_provider
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AuthLog> $authentications
 * @property-read int|null $authentications_count
 * @property-read AuthLog|null $latestAuthentication
 *
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Client> $oauthApps
 * @property-read int|null $oauth_apps_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TwoFAccountShare> $borrowedTwofaccounts
 * @property-read int|null $borrowed_twofaccounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TwoFAccountShare> $sharedTwofaccounts
 * @property-read int|null $shared_twofaccounts_count
 */
#[Fillable(['name', 'email', 'password', 'oauth_id', 'oauth_provider'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasLocalePreference, OAuthenticatable, WebAuthnAuthenticatable
{
    use HasApiTokens, Notifiable;
    use HasAuthenticationLog;

    /**
     * @use HasFactory<UserFactory>
     */
    use HasFactory;

    use WebAuthnAuthentication, WebAuthnManageCredentials;

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'is_admin'           => 'boolean',
        'twofaccounts_count' => 'integer',
        'groups_count'       => 'integer',
    ];

    /**
     * User exposed observable events.
     *
     * These are extra user-defined events observers may subscribe to.
     *
     * @var string[]
     */
    protected $observables = ['demoting'];

    /**
     * Get the user's preferred locale.
     */
    public function preferredLocale() : string
    {
        return strval($this->preferences['lang']);
    }

    /**
     * Scope a query to only include admin users.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Determine if the user is an administrator.
     *
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->is_admin;
    }

    /**
     * Grant administrator permissions to the user.
     */
    public function promoteToAdministrator(bool $promote = true) : bool
    {
        $eventResult = $promote ? $this->fireModelEvent('promoting') : $this->fireModelEvent('demoting');

        if ($promote == false && $eventResult === false) {
            return false;
        }

        $this->is_admin = $promote;

        return true;
    }

    /**
     * Say if the user is the only registered administrator
     *
     * @return bool
     */
    public function isLastAdministrator()
    {
        $admins = User::admins()->get();

        return $admins->contains($this->id) && $admins->count() === 1;
    }

    /**
     * Reset user password with a 12 chars random string.
     *
     * @return void
     */
    public function resetPassword()
    {
        $this->password = Hash::make(Str::password(12));

        event(new PasswordReset($this));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify((new ResetPassword($token))->locale($this->preferredLocale() == 'browser' ? App::currentLocale() : $this->preferredLocale()));
    }

    /**
     * Get Preferences attribute
     *
     * @param  string  $value
     * @return Collection<array-key, mixed>
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
     * @return HasMany<TwoFAccount, $this>
     */
    public function twofaccounts()
    {
        return $this->hasMany(TwoFAccount::class);
    }

    /**
     * Get the Groups of the user.
     *
     * @return HasMany<Group, $this>
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get TwoFAccount shared by the user.
     *
     * @return HasMany<TwoFAccountShare, $this>
     */
    public function sharedTwofaccounts()
    {
        $query = $this->hasMany(TwoFAccountShare::class, 'created_by_user_id');

        if (! Settings::get('enableSharing')) {
            return $query->where('id', -1); // Return empty result if sharing is disabled
        }

        if (! Settings::get('enableAllUsersSharingScope')) {
            $query->where('scope', '!=', TwoFAccountShare::SCOPE_ALL_USERS); // Exclude all users shares
        }

        return $query;
    }

    /**
     * Get TwoFAccounts shared with the user.
     *
     * @return HasMany<TwoFAccountShare, $this>
     */
    public function borrowedTwofaccounts()
    {
        $query = $this->hasMany(TwoFAccountShare::class, 'shared_with_user_id');

        if (! Settings::get('enableSharing')) {
            return $query->where('id', -1); // Return empty result if sharing is disabled
        }

        if (! Settings::get('enableAllUsersSharingScope')) {
            $query->where('scope', '!=', TwoFAccountShare::SCOPE_ALL_USERS); // Exclude all users shares
        }

        return $query;
    }

    /**
     * Determine if the user has shared the given TwoFAccount.
     */
    public function isSharing(TwoFAccount $twofaccount) : bool
    {
        return $this->sharedTwofaccounts()
            ->where('twofaccount_id', $twofaccount->id)
            ->exists();
    }

    /**
     * Compare 2 Users
     */
    public function equals(self $other) : bool
    {
        return $this->name === $other->name &&
            $this->email === $other->email &&
            $this->oauth_id == $other->oauth_id &&
            $this->oauth_provider == $other->oauth_provider;
    }
}
