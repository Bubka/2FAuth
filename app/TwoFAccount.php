<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwoFAccount extends Model
{

        protected $fillable = ['service', 'account', 'uri', 'icon'];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'twofaccounts';
}
