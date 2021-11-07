<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TwoFAccountStoreResource extends JsonResource
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
            'otp_type'      => $this->otp_type,
            'account'       => $this->account,
            'service'       => $this->service,
            'icon'          => $this->icon,
            'secret'        => $this->when(
                                    !$request->has('withSecret') || (int) filter_var($request->input('withSecret'), FILTER_VALIDATE_BOOLEAN) == 1,
                                    $this->secret
                                ),
            'digits'        => $this->digits,
            'algorithm'     => $this->algorithm,
            'period'        => $this->period,
            'counter'       => $this->counter
        ];
    }
}