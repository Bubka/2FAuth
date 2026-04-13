<?php

namespace App\Api\v1\Resources;

/**
 * @property mixed $id
 * @property mixed $group_id
 * @property \App\Models\User|null $user
 *
 * @method \Illuminate\Database\Eloquent\Collection<array-key, \App\Models\TwoFAccount> loadCount(string $relations)
 * @method bool isSharedWith(\App\Models\User $user)
 * @method \App\Models\Dto\TotpDto|\App\Models\Dto\HotpDto getOtp(int $time)
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
        $isBorrowed = $this->isSharedWith($request->user());
        $isShared   = $request->user()->isSharing($this->resource);

        return array_merge(
            [
                'id'          => (int) $this->id,
                'group_id'    => is_null($this->group_id) ? null : (int) $this->group_id,
                'is_borrowed' => $this->when($isBorrowed, true),
                'borrowed_by' => $this->when($isBorrowed, $this->user?->name),
                'is_shared'   => $this->when($isShared, true),
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

                        return collect([
                            'password'      => $otp->password,
                            'generated_at'  => $otp->generated_at,
                            'next_password' => $otp->next_password,
                        ]);
                    }
                ),
            ],
        );
    }
}
