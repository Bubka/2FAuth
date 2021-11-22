<?php

namespace App\Api\v1\Resources;

class TwoFAccountReadResource extends TwoFAccountStoreResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(
            [
                'id'            => (int) $this->id,
                'group_id'      => is_null($this->group_id) ? null : (int) $this->group_id,
            ],
            parent::toArray($request)
        );
    }
}