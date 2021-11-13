<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'twofaccounts_count'    => is_null($this->twofaccounts_count) ? 0 : $this->twofaccounts_count,
        ];
    }
}