<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\TwoFAccountShare, \App\Models\User> $borrowedTwofaccounts
 */
class USerShareRecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $twofaccount = $request->route('twofaccount');
        $isShared    = $this->borrowedTwofaccounts->where('twofaccount_id', $twofaccount->id)->first();

        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'isSharedWith' => $isShared != null,
        ];
    }
}
