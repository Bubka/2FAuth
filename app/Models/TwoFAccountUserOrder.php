<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\TwoFAccountUserOrder
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int $user_id
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TwoFAccount $twofaccount
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountUserOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountUserOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountUserOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwoFAccountUserOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwoFAccountUserOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwoFAccountUserOrder wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwoFAccountUserOrder whereTwofaccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwoFAccountUserOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwoFAccountUserOrder whereUserId($value)
 * @mixin \Eloquent
 */
#[Fillable(['twofaccount_id', 'user_id', 'position'])]
class TwoFAccountUserOrder extends Model
{
    /**
     * @var string
     */
    protected $table = 'twofaccount_user_orders';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id' => 'integer',
        'user_id'        => 'integer',
        'position'       => 'integer',
    ];

    /**
     * @return BelongsTo<TwoFAccount, $this>
     */
    public function twofaccount()
    {
        return $this->belongsTo(TwoFAccount::class, 'twofaccount_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
