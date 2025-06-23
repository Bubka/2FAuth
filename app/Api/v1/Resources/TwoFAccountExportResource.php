<?php

namespace App\Api\v1\Resources;

use App\Facades\IconStore;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $otp_type
 * @property string $account
 * @property string $service
 * @property string|null $icon
 * @property string|null $icon_file
 * @property string $secret
 * @property int $digits
 * @property string $algorithm
 * @property int|null $period
 * @property int|null $counter
 * @property string $legacy_uri
 *
 * @method string getURI()
 */
class TwoFAccountExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $request->has('otpauth') && $request->boolean('otpauth')
            ? [
                'uri' => urldecode($this->getURI()),
            ]
            : [
                'otp_type'   => $this->otp_type,
                'account'    => $this->account,
                'service'    => $this->service,
                'icon'       => $this->icon,
                'icon_mime'  => $this->icon && IconStore::exists($this->icon) ? IconStore::mimeType($this->icon) : null,
                'icon_file'  => $this->icon && IconStore::exists($this->icon) ? base64_encode(IconStore::get($this->icon)) : null,
                'secret'     => $this->secret,
                'digits'     => (int) $this->digits,
                'algorithm'  => $this->algorithm,
                'period'     => is_null($this->period) ? null : (int) $this->period,
                'counter'    => is_null($this->counter) ? null : (int) $this->counter,
                'legacy_uri' => $this->legacy_uri,
                'is_shared'  => (bool) $this->is_shared,
            ];
    }
}
