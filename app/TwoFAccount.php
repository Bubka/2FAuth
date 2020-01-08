<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TwoFAccount extends Model
{

        protected $fillable = ['service', 'account', 'uri', 'icon'];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'twofaccounts';


    /**
     * Null empty icon resource has gone
     *
     * @param  string  $value
     * @return string
     */
    public function getIconAttribute($value)
    {

        if( !Storage::exists('public/icons/' . pathinfo($value)['basename']) ) {

            return '';
        }

        return $value;
    }
}
