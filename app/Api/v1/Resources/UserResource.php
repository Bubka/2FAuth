<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed $id
 * @property string $name
 * @property string $email
 * @property string $oauth_provider
 * @property \Illuminate\Support\Collection<array-key, mixed> $preferences
 * @property string $is_admin
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
            'is_admin'               => $this->is_admin,
        ];
    }
}
