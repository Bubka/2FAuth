<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TwoFAccount extends Model
{
        use SoftDeletes;

        protected $fillable = ['service', 'account', 'uri', 'icon'];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'twofaccounts';
}
