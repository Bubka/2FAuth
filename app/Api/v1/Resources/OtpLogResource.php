<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $requester_name
 * @property string $requester_email
 * @property string $owner_name
 * @property string $owner_email
 * @property string $twofaccount_account
 * @property string $twofaccount_service
 * @property string $ip_address
 * @property string $generated_at
 * @property string $otp_type
 * @property int $counter
 */
class OtpLogResource extends JsonResource
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
            'requester_name'      => $this->requester_name,
            'requester_email'     => $this->requester_email,
            'owner_name'          => $this->owner_name,
            'owner_email'         => $this->owner_email,
            'twofaccount_account' => $this->twofaccount_account,
            'twofaccount_service' => $this->twofaccount_service,
            'ip_address'          => $this->ip_address,
            'generated_at'        => $this->generated_at,
            'otp_type'            => $this->otp_type,
            'counter'             => $this->counter,
        ];
    }
}
