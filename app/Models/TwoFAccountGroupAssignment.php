<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\TwoFAccountGroupAssignment
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int $group_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Group $group
 * @property-read TwoFAccount $twofaccount
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountGroupAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountGroupAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountGroupAssignment query()
 *
 * @mixin \Eloquent
 */
#[Fillable(['twofaccount_id', 'group_id', 'user_id'])]
class TwoFAccountGroupAssignment extends Model
{
    /**
     * @var string
     */
    protected $table = 'twofaccount_group_assignments';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id' => 'integer',
        'group_id'       => 'integer',
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
     * @return BelongsTo<Group, $this>
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
