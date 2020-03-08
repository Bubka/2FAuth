<?php

namespace App;

use OTPHP\HOTP;
use OTPHP\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class TwoFAccount extends Model
{
    /**
     * model's array form.
     *
     * @var array
     */
    protected $fillable = ['service', 'account', 'uri', 'icon'];


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'twofaccounts';


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['type', 'counter'];


    /**
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleted(function ($model) {
            Storage::delete('public/icons/' . $model->icon);
        });
    }


    /**
     * Null empty icon resource has gone
     *
     * @param  string  $value
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getIconAttribute($value)
    {
        if (\App::environment('testing') == false) {
            if( !Storage::exists('public/icons/' . $value) ) {

                return '';
            }
        }

        return $value;
    }


    /**
     * Prevent setting a missing icon
     *
     * @param  string  $value
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function setIconAttribute($value)
    {

        if( !Storage::exists('public/icons/' . $value) && \App::environment('testing') == false ) {

            $this->attributes['icon'] = '';
        }
        else {

            $this->attributes['icon'] = $value;
        }
    }



    /**
    * Get the account type.
    *
    * @return string
    */
    public function getTypeAttribute()
    {
        
        return substr( $this->uri, 0, 15 ) === "otpauth://totp/" ? 'totp' : 'hotp';
    }

    /**
    * Get the account counter in case of HOTP.
    *
    * @return integer
    */
    public function getCounterAttribute()
    {
        
        if( $this->type === 'hotp' ) {
            $otp = Factory::loadFromProvisioningUri($this->uri);

            return $otp->getCounter();
        }

        return null;
    }


    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    // public function setUriAttribute($value)
    // {
    //     $this->attributes['uri'] = encrypt($value);
    // }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    // public function getUriAttribute($value)
    // {
    //     try {

    //         return decrypt($value);

    //     } catch (DecryptException $e) {

    //         return null;
    //     }

    // }

}
