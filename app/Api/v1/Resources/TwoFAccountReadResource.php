<?php

namespace App\Api\v1\Resources;

/**
 * @property mixed $id
 * @property mixed $group_id
 *
 * @method App\Models\Dto\TotpDto|App\Models\Dto\HotpDto getOtp(int $time)
 */
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
                'id'       => (int) $this->id,
                'group_id' => is_null($this->group_id) ? null : (int) $this->group_id,
            ],
            parent::toArray($request),
            [
                'otp' => $this->when(
                    $this->otp_type != 'hotp' && ($request->has('withOtp') || (int) filter_var($request->input('withOtp'), FILTER_VALIDATE_BOOLEAN) == 1),
                    function () use ($request) {
                        /**
                         * @var \App\Models\Dto\TotpDto|\App\Models\Dto\HotpDto
                         */
                        $otp = $this->getOtp($request->at);

                        return collect(['password' => $otp->password, 'generated_at' => $otp->generated_at]);
                    }
                ),
            ],
        );
    }
}
