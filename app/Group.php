<?php

namespace App;

use App\Classes\Options;
use Illuminate\Database\Eloquent\Model;

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
    protected $appends = ['isActive'];


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
     * Get the TwoFAccounts of the group.
     */
    public function twofaccounts()
    {
        return $this->hasMany('App\TwoFAccount');
    }



    /**
    * Get the group 
    *
    * @return integer
    */
    public function getIsActiveAttribute()
    {
        $activeGroupId = intval(Options::get('activeGroup'));
        
        return $this->id === $activeGroupId ? true : false;
    }

}
