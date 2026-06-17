<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\TwoFAccountShare
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int|null $shared_with_user_id
 * @property string $scope
 * @property int $created_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TwoFAccount $twofaccount
 * @property-read User|null $sharedWithUser
 * @property-read User $createdByUser
 */
#[Fillable(['twofaccount_id', 'shared_with_user_id', 'scope', 'created_by_user_id'])]
class TwoFAccountShare extends Model
{
    public const SCOPE_USER = 'user';

    public const SCOPE_ALL_USERS = 'all_users';

    /**
     * @var string
     */
    protected $table = 'twofaccount_shares';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id'      => 'integer',
        'shared_with_user_id' => 'integer',
        'created_by_user_id'  => 'integer',
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
    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForAllUsers($query)
    {
        return $query->where('scope', self::SCOPE_ALL_USERS);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForUser($query, int $userId)
    {
        return $query
            ->where('scope', self::SCOPE_USER)
            ->where('shared_with_user_id', $userId);
    }
}
