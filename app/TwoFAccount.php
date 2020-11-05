<?php

namespace App;

use Exception;
use OTPHP\HOTP;
use OTPHP\Factory;
use App\Classes\Options;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class TwoFAccount extends Model implements Sortable
{

    use SortableTrait;


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
    protected $appends = ['otpType', 'counter'];


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
     * Scope a query to only include TwoFAccounts of a given group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfGroup($query, $groupId)
    {
        if( $groupId ) {
            return $query->where('group_id', $groupId);
        }

        return $query;
    }


    /**
     * Sortable settings
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];


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
    * Get the account OTP type.
    *
    * @return string
    */
    public function getOtpTypeAttribute()
    {
        switch (substr( $this->uri, 0, 15 )) {

            case "otpauth://totp/" :
                return 'totp';
                break;

            case "otpauth://hotp/" :
                return 'hotp';
                break;

            default:
                return null;
        }
    }

    /**
    * Get the account counter in case of HOTP.
    *
    * @return integer
    */
    public function getCounterAttribute()
    {
        
        if( $this->otpType === 'hotp' ) {
            $otp = Factory::loadFromProvisioningUri($this->uri);

            return $otp->getCounter();
        }

        return null;
    }


    /**
     * Set encrypted uri
     *
     * @param  string  $value
     * @return void
     */
    public function setUriAttribute($value)
    {
        $this->attributes['uri'] = Options::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }

    /**
     * Get decyphered uri
     *
     * @param  string  $value
     * @return string
     */
    public function getUriAttribute($value)
    {
        if( Options::get('useEncryption') )
        {
            try {
                return Crypt::decryptString($value);
            }
            catch (Exception $e) {
                return '*encrypted*';
            }
        }
        else {
            return $value;
        }
    }


    /**
     * Set encrypted account
     *
     * @param  string  $value
     * @return void
     */
    public function setAccountAttribute($value)
    {
        $this->attributes['account'] = Options::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }

    /**
     * Get decyphered account
     *
     * @param  string  $value
     * @return string
     */
    public function getAccountAttribute($value)
    {
        if( Options::get('useEncryption') )
        {
            try {
                return Crypt::decryptString($value);
            }
            catch (Exception $e) {
                return '*encrypted*';
            }
        }
        else {
            return $value;
        }
    }

}
