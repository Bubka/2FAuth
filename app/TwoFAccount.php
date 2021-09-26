<?php

namespace App;

use Exception;
// use App\Services\SettingServiceInterface;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class TwoFAccount extends Model implements Sortable
{

    use SortableTrait;

    /**
     * A human understandable value to return when attribute decryption fails
     */
    private const INDECIPHERABLE = '*indecipherable*';


    /**
     * model's array form.
     *
     * @var array
     */
    protected $fillable = [];


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
    public $appends = [];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];


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
     * Settings for @spatie/eloquent-sortable package
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];


    /**
     * Increment the hotp counter by 1
     * @return void
     */
    // public function increaseHotpCounter() : void
    // {
    //     if( $this->otpType === 'hotp' ) {
    //         $this->counter = $this->counter + 1;
    //         $this->refreshUri();
    //     }
    // }


    /**
     * Get is_deciphered attribute
     *
     * @return bool
     *
     */
    // public function getIsDecipheredAttribute()
    // {
    //     $this->attributes['is_deciphered'] = $this->legacy_uri === self::INDECIPHERABLE || $this->account === self::INDECIPHERABLE || $this->secret === self::INDECIPHERABLE ? false : true;
    //     // $this->attributes['is_deciphered'] = 'toto';
    // }


    /**
     * Get legacy_uri attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getLegacyUriAttribute($value)
    {
        
        return $this->decryptOrReturn($value);
    }
    /**
     * Set legacy_uri attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setLegacyUriAttribute($value)
    {
        // Encrypt if needed
        $this->attributes['legacy_uri'] = $this->encryptOrReturn($value);
    }


    /**
     * Get account attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getAccountAttribute($value)
    {
        
        return $this->decryptOrReturn($value);
    }
    /**
     * Set account attribute
     *
     * @param string $value
     * @return void
     */
    public function setAccountAttribute($value)
    {
        // Encrypt when needed
        $this->attributes['account'] = $this->encryptOrReturn($value);
    }


    /**
     * Get secret attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getSecretAttribute($value)
    {

        return $this->decryptOrReturn($value);
    }
    /**
     * Set secret attribute
     *
     * @param string $value
     * @return void
     */
    public function setSecretAttribute($value)
    {
        // Encrypt when needed
        $this->attributes['secret'] = $this->encryptOrReturn($value);
    }


    /**
     * Returns an acceptable value
     */
    private function decryptOrReturn($value)
    {
        $settingService = resolve('App\Services\SettingServiceInterface');

        // Decipher when needed
        if ( $settingService->get('useEncryption') )
        {
            try {
                return Crypt::decryptString($value);
            }
            catch (Exception $e) {
                return self::INDECIPHERABLE;
            }
        }
        else {
            return $value;
        }
    }


    /**
     * Encrypt a value
     */
    private function encryptOrReturn($value)
    {
        $settingService = resolve('App\Services\SettingServiceInterface');

        // should be replaced by laravel 8 attribute encryption casting
        return $settingService->get('useEncryption') ? Crypt::encryptString($value) : $value;
    }

}