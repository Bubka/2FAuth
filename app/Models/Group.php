<?php

namespace App\Models;

use App\Events\GroupDeleting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{

    use HasFactory;

    /**
     * model's array form.
     *
     * @var array
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
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];


    /**
     * The attributes that should be cast.
     *
     * @var array
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
    ];


    /**
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            // @codeCoverageIgnoreStart
            Log::info(sprintf('Group %s deleted', var_export($model->name, true)));
            // @codeCoverageIgnoreEnd
        });
    }


    /**
     * Get the TwoFAccounts of the group.
     */
    public function twofaccounts()
    {
        return $this->hasMany('App\Models\TwoFAccount');
    }
}
