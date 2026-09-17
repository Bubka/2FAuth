<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\TwoFAccountUserFavorite
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int $user_id
 * @property-read TwoFAccount $twofaccount
 * @property-read User $user
 */
#[Table(timestamps: false)]
#[Fillable(['twofaccount_id', 'user_id'])]
class TwoFAccountUserFavorite extends Model
{
    /**
     * @var string
     */
    protected $table = 'twofaccount_user_favorites';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id' => 'integer',
        'user_id'        => 'integer',
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
