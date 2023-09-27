<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property string $name
 * @property string $email
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
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'preferences' => $this->preferences,
            'is_admin'    => $this->is_admin,
        ];
    }
}
