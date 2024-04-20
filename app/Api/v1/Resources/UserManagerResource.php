<?php

namespace App\Api\v1\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\TokenRepository;

/**
 * @property mixed $id
 * @property string $name
 * @property string $email
 * @property string $oauth_provider
 * @property \Illuminate\Support\Collection<array-key, mixed> $preferences
 * @property string $is_admin
 * @property string $last_seen_at
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $twofaccounts_count
 */
class UserManagerResource extends UserResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'info';

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        $password_reset = null;

        // Password reset token
        $resetToken = DB::table(config('auth.passwords.users.table'))->where(
            'email', $this->resource->getEmailForPasswordReset()
        )->first();

        if ($resetToken) {
            $password_reset = $this->tokenExpired($resetToken->created_at)
                ? 0
                : $resetToken->created_at;
        }

        // Personal Access Tokens (PATs)
        $tokenRepository = App::make(TokenRepository::class);
        $tokens          = $tokenRepository->forUser($this->resource->getAuthIdentifier());

        $PATs_count = $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && ! $token->revoked; /** @phpstan-ignore-line */
        })->count();

        $this->with = [
            'password_reset'               => $password_reset,
            'valid_personal_access_tokens' => $PATs_count,
            'webauthn_credentials'         => $this->resource->webAuthnCredentials()->count(),
        ];
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        // See Illuminate\Auth\Passwords\DatabaseTokenRepository
        return Carbon::parse($createdAt)->addSeconds(config('auth.passwords.users.expires', 60) * 60)->isPast();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            parent::toArray($request),
            [
                'twofaccounts_count' => is_null($this->twofaccounts_count) ? 0 : $this->twofaccounts_count,
                'last_seen_at'       => Carbon::parse($this->last_seen_at)->locale(App::getLocale())->diffForHumans(),
                'created_at'         => Carbon::parse($this->created_at)->locale(App::getLocale())->diffForHumans(),
            ]
        );
    }
}
