<?php

namespace App\Api\v1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
        return [
            'otp_type'   => $this->otp_type,
            'account'    => $this->account,
            'service'    => $this->service,
            'icon'       => $this->icon,
            'icon_mime'  => $this->icon ? Storage::disk('icons')->mimeType((string) $this->icon) : null,
            'icon_file'  => $this->icon ? base64_encode(Storage::disk('icons')->get((string) $this->icon)) : null,
            'secret'     => $this->secret,
            'digits'     => (int) $this->digits,
            'algorithm'  => $this->algorithm,
            'period'     => is_null($this->period) ? null : (int) $this->period,
            'counter'    => is_null($this->counter) ? null : (int) $this->counter,
            'legacy_uri' => $this->legacy_uri,
        ];
    }
}
