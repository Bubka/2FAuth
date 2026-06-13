<?php

namespace App\Models;

use Database\Factories\OtpLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $requester_id
 * @property string $requester_name
 * @property string $requester_email
 * @property int|null $owner_id
 * @property string $owner_name
 * @property string $owner_email
 * @property int|null $twofaccount_id
 * @property string $ip_address
 * @property string $otp_type
 * @property int|null $counter
 * @property Carbon|null $generated_at
 * @property-read User|null $requester
 * @property-read User|null $owner
 * @property-read TwoFAccount|null $twofaccount
 *
 * @mixin \Eloquent
 *
 * @method static \Database\Factories\OtpLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> query()
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereTwofaccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<OtpLog> whereGeneratedAt($value)
 */
#[Fillable(['requester_id', 'requester_name', 'requester_email', 'owner_id', 'owner_name', 'owner_email', 'twofaccount_id', 'ip_address', 'generated_at', 'otp_type', 'counter'])]
class OtpLog extends Model
{
    /**
     * @use HasFactory<OtpLogFactory>
     */
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'generated_at' => 'datetime',
    ];

    /**
     * Get the user that generated the OTP.
     *
     * @return BelongsTo<User, $this>
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the owner of TwoFAccount at the time of the OTP.
     *
     * @return BelongsTo<User, $this>
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the twofaccount associated with the OTP.
     *
     * @return BelongsTo<TwoFAccount, $this>
     */
    public function twofaccount()
    {
        return $this->belongsTo(TwoFAccount::class);
    }

    /**
     * Get otp logs for the provided timespan (in month)
     *
     * @param  Builder<OtpLog>  $query
     * @return Builder<OtpLog>
     */
    public function scopeByPeriod($query, int $period = 1)
    {
        $from = Carbon::now()->subMonths($period);

        return $query->where('generated_at', '>=', $from);
    }
}
