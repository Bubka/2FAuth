<?php

namespace App\Api\v1\Resources;

use App\Facades\Settings;
use App\Models\TwoFAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed $id
 * @property string $name
 * @property string $email
 * @property string $oauth_provider
 * @property Collection<array-key, mixed> $preferences
 * @property string $is_admin
 * @property int|null $twofaccounts_count
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'email'                  => $this->email,
            'oauth_provider'         => $this->oauth_provider,
            'authenticated_by_proxy' => Auth::getDefaultDriver() === 'reverse-proxy-guard',
            'preferences'            => $this->preferences,
            'appSettings'            => [
                'enableSharing'              => Settings::get('enableSharing'),
                'enableAllUsersSharingScope' => Settings::get('enableAllUsersSharingScope'),
            ],
            'is_admin' => $this->is_admin,
            'twofaccount_count' => TwoFAccount::visibleTo($this->resource)->count(),
        ];
    }
}
