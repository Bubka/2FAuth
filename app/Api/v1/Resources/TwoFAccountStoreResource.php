<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $otp_type
 * @property string $account
 * @property string $service
 * @property string $icon
 * @property string $secret
 * @property int $digits
 * @property string $algorithm
 * @property int|null $period
 * @property int|null $counter
 */
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
            'otp_type' => $this->otp_type,
            'account'  => $this->account,
            'service'  => $this->service,
            'icon'     => $this->icon,
            'secret'   => $this->when(
                ! $request->has('withSecret') || (int) filter_var($request->input('withSecret'), FILTER_VALIDATE_BOOLEAN) == 1,
                $this->secret
            ),
            'digits'    => (int) $this->digits,
            'algorithm' => $this->algorithm,
            'period'    => is_null($this->period) ? null : (int) $this->period,
            'counter'   => is_null($this->counter) ? null : (int) $this->counter,
        ];
    }
}
