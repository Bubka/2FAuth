<?php

namespace App\Models;

use App\Events\GroupDeleted;
use App\Events\GroupDeleting;
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
 */
class Group extends Model
{
    use HasFactory;

    /**
     * model's array form.
     *
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
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
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleting' => GroupDeleting::class,
        'deleted'  => GroupDeleted::class,
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
        static::deleted(function (object $model) {
            Log::info(sprintf('Group %s (id #%d) deleted ', var_export($model->name, true), $model->id));
        });
    }

    /**
     * Get the TwoFAccounts of the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TwoFAccount>
     */
    public function twofaccounts()
    {
        return $this->hasMany(\App\Models\TwoFAccount::class);
    }

   /**
    * Get the user that owns the group.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\Group>
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
