<?php

namespace App\Api\v1\Resources;

use App\Models\TwoFAccountShare;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property mixed $id
 * @property string $name
 * @property HasMany<TwoFAccountShare, User> $borrowedTwofaccounts
 */
class UserShareRecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $twofaccount = $request->route('twofaccount');
        $isShared    = $this->borrowedTwofaccounts->where('twofaccount_id', $twofaccount->id)->first();
        $sharedSince = $isShared ? $isShared->created_at : null;
        $tz          = $request->user()?->preferences['timezone'] ?? config('app.timezone');

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'is_shared_with'  => $isShared != null,
            'is_shared_since' => $this->when($isShared != null, Carbon::parse($sharedSince)->tz($tz)->toDayDateTimeString()),
        ];
    }
}
