<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Group extends Model
{

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
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($model) {
            TwoFAccount::where('group_id', $model->id)
                        ->update(
                            ['group_id' => NULL]
                        );
        });

        static::deleted(function ($model) {
            Log::info(sprintf('Group %s deleted', var_export($model->name, true)));
        });
    }


    /**
     * Get the TwoFAccounts of the group.
     */
    public function twofaccounts()
    {
        return $this->hasMany('App\TwoFAccount');
    }
}
