<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TwoFAccountUserOrder
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int $user_id
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TwoFAccount $twofaccount
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountUserOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountUserOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountUserOrder query()
 *
 * @mixin \Eloquent
 */
class TwoFAccountUserOrder extends Model
{
    /**
     * @var string
     */
    protected $table = 'twofaccount_user_orders';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'twofaccount_id',
        'user_id',
        'position',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id' => 'integer',
        'user_id'        => 'integer',
        'position'       => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\TwoFAccount, $this>
     */
    public function twofaccount()
    {
        return $this->belongsTo(TwoFAccount::class, 'twofaccount_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
