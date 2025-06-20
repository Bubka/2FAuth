<?php

namespace App\Models;

use App\Events\GroupDeleted;
use Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Group
 *
 * @property int $twofaccounts_count
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TwoFAccount[] $twofaccounts
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\GroupFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUserId($value)
 *
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Group orphans()
 */
class Group extends Model
{
    /**
     * @use HasFactory<GroupFactory>
     */
    use HasFactory;

    /**
     * model's array form.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccounts_count' => 'integer',
        'user_id'            => 'integer',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => GroupDeleted::class,
    ];

    /**
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function (object $model) {
            Log::info(sprintf('Group %s (id #%d) created for user ID #%s', var_export($model->name, true), $model->id, $model->user_id));
        });
        static::updated(function (object $model) {
            Log::info(sprintf('Group %s (id #%d) updated by user ID #%s', var_export($model->name, true), $model->id, $model->user_id));
        });
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // The All group is a virtual group with id==0.
        // It never exists in database so we enforce the route binding
        // resolution logic to return an instance instead of not found.
        if ($value === '0') {
            $group = new self([
                'name' => __('message.all'),
            ]);
            $group->id = 0;

            return $group;
        } else {
            return parent::resolveRouteBinding($value, $field);
        }
    }

    /**
     * Get the TwoFAccounts of the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\TwoFAccount, $this>
     */
    public function twofaccounts()
    {
        return $this->hasMany(\App\Models\TwoFAccount::class);
    }

    /**
     * Get the user that owns the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Scope a query to only include orphan (userless) groups.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<User>  $query
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    public function scopeOrphans($query)
    {
        return $query->where('user_id', null);
    }
}
