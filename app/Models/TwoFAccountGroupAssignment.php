<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TwoFAccountGroupAssignment
 *
 * @property int $id
 * @property int $twofaccount_id
 * @property int $group_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group $group
 * @property-read \App\Models\TwoFAccount $twofaccount
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountGroupAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountGroupAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccountGroupAssignment query()
 *
 * @mixin \Eloquent
 */
class TwoFAccountGroupAssignment extends Model
{
    /**
     * @var string
     */
    protected $table = 'twofaccount_group_assignments';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'twofaccount_id',
        'group_id',
        'user_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccount_id' => 'integer',
        'group_id'       => 'integer',
        'user_id'        => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\TwoFAccount, $this>
     */
    public function twofaccount()
    {
        return $this->belongsTo(TwoFAccount::class, 'twofaccount_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Group, $this>
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
