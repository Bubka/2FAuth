<?php

namespace App\Models;

use App\Events\GroupDeleted;
use Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * App\Models\Group
 *
 * @property int $twofaccounts_count
 * @property int $id
 * @property string $name
 * @property bool $show_in_chips
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read Collection|TwoFAccount[] $twofaccounts
 * @property-read User|null $user
 * @method static \Database\Factories\GroupFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group orphans()
 * @property-read Collection<int, \App\Models\TwoFAccountGroupAssignment> $twofaccountGroupAssignments
 * @property-read int|null $twofaccount_group_assignments_count
 * @method static Builder<static>|Group ordered(string $direction = 'asc')
 * @method static Builder<static>|Group whereOrderColumn($value)
 * @method static Builder<static>|Group whereShowInChips($value)
 * @mixin \Eloquent
 */
#[Fillable(['name', 'show_in_chips'])]
#[Hidden(['created_at', 'updated_at'])]
class Group extends Model implements Sortable
{
    /**
     * @use HasFactory<GroupFactory>
     */
    use HasFactory, SortableTrait;

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'twofaccounts_count' => 'integer',
        'user_id'            => 'integer',
        'show_in_chips'      => 'boolean',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
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
     * Settings for @spatie/eloquent-sortable package
     *
     * @var array
     */
    public $sortable = [
        'order_column_name'  => 'order_column',
        'sort_when_creating' => true,
    ];

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // The All group is a virtual group with id==0.
        // It never exists in database so we enforce the route binding
        // resolution logic to return an instance instead of not found.
        if ($value === '0') {
            $group = new self([
                'name' => __('label.all'),
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
     * @return BelongsToMany<TwoFAccount, $this>
     */
    public function twofaccounts() : BelongsToMany
    {
        return $this->belongsToMany(TwoFAccount::class, 'twofaccount_group_assignments', 'group_id', 'twofaccount_id')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    /**
     * Get assignment rows for the group.
     *
     * @return HasMany<TwoFAccountGroupAssignment, $this>
     */
    public function twofaccountGroupAssignments() : HasMany
    {
        return $this->hasMany(TwoFAccountGroupAssignment::class, 'group_id');
    }

    /**
     * Get the user that owns the group.
     *
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include orphan (userless) groups.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeOrphans($query)
    {
        return $query->where('user_id', null);
    }
}
