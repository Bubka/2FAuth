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
                'id'            => $this->id,
                'group_id'      => $this->group_id,
            ],
            parent::toArray($request)
        );
    }
}