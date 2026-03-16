<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TwoFAccountShare
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int|null $shared_with_user_id
 * @property string $scope
 * @property int $created_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TwoFAccount $twofaccount
 * @property-read \App\Models\User|null $sharedWithUser
 * @property-read \App\Models\User $createdByUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountShare forAllUsers()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountShare forUser(int $userId)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountShare newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountShare newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountShare query()
 *
 * @mixin \Eloquent
 */
class TwoFAccountShare extends Model
{
    public const SCOPE_USER = 'user';

    public const SCOPE_ALL_USERS = 'all_users';

    /**
     * @var string
     */
    protected $table = 'twofaccount_shares';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'twofaccount_id',
        'shared_with_user_id',
        'scope',
        'created_by_user_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id' => 'integer',
        'shared_with_user_id' => 'integer',
        'created_by_user_id' => 'integer',
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
    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeForAllUsers($query)
    {
        return $query->where('scope', self::SCOPE_ALL_USERS);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<static>  $query
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function scopeForUser($query, int $userId)
    {
        return $query
            ->where('scope', self::SCOPE_USER)
            ->where('shared_with_user_id', $userId);
    }
}
