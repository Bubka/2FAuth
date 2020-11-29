<?php

namespace App;

use Exception;
use OTPHP\TOTP;
use OTPHP\HOTP;
use OTPHP\Factory;
use App\Classes\Options;
use ParagonIE\ConstantTime\Base32;
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
    protected $appends = ['token', 'isConsistent', 'otpType', 'secret', 'algorithm', 'digits', 'totpPeriod', 'totpTimestamp', 'hotpCounter', 'imageLink'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['token', 'uri', 'secret', 'algorithm', 'created_at', 'updated_at'];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'group_id' => 'integer',
        'order_column' => 'integer',
    ];


    /**
     *  An OTP object from package Spomky-Labs/otphp
     *
     * @var OTPHP/TOTP || OTPHP/HOTP
     */
    protected $otp, $timestamp, $badUri;


    /**
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            try {
                $model->populateFromUri($model->uri);
            }
            catch( \App\Exceptions\InvalidOtpParameterException $e ) {
                $model->badUri = true;
            }
        });

        static::saving(function ($model) {
            $model->refreshUri();
        });
        
        static::deleted(function ($model) {
            Storage::delete('public/icons/' . $model->icon);
        });
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
     *  Populate attributes with direct values
     * @param  Array|array $attrib All attributes to be set
     */
    public function populate(Array $attrib = [])
    {
        // The Type and Secret attributes are mandatory
        // All other attributes have default value set by OTPHP

        if( $attrib['otpType'] !== 'totp' && $attrib['otpType'] !== 'hotp' ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'otpType' => __('errors.not_a_supported_otp_type')
            ]);
        }

        if( !$attrib['secret'] ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'secret' => __('errors.cannot_create_otp_without_secret')
            ]); 
        }

        try {
            // Create an OTP object using our secret but with default parameters
            $secret = $attrib['secretIsBase32Encoded'] === 1 ? $attrib['secret'] : Base32::encodeUpper($attrib['secret']);

            $this->otp = $attrib['otpType'] === 'totp' ? TOTP::create($secret) : HOTP::create($secret);

            // and we change parameters if needed
            if (array_key_exists('service', $attrib) && $attrib['service']) {
                $this->service = $attrib['service'];
                $this->otp->setIssuer( $attrib['service'] );
            }

            if (array_key_exists('account', $attrib) && $attrib['account']) {
                $this->account = $attrib['account'];
                $this->otp->setLabel( $attrib['account'] );
            }

            if (array_key_exists('icon', $attrib) && $attrib['icon'])
                { $this->icon = $attrib['icon']; }

            if (array_key_exists('digits', $attrib) && $attrib['digits'] > 0)
                { $this->otp->setParameter( 'digits', (int) $attrib['digits'] ); }

            if (array_key_exists('algorithm', $attrib) && $attrib['algorithm'])
                { $this->otp->setParameter( 'algorithm', $attrib['algorithm'] ); }

            if (array_key_exists('totpPeriod', $attrib) && $attrib['totpPeriod'] && $attrib['otpType'] === 'totp')
                { $this->otp->setParameter( 'period', (int) $attrib['totpPeriod'] ); }

            if (array_key_exists('hotpCounter', $attrib) && $attrib['hotpCounter'] && $attrib['otpType'] === 'hotp')
                { $this->otp->setParameter( 'counter', (int) $attrib['hotpCounter'] ); }

        }
        catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'qrcode' => __('errors.cannot_create_otp_with_those_parameters')
            ]);
        }

    }


    /**
     * Generate a token which is valid at the current time
     * @return string The generated token
     */
    public function generateToken() : string
    {
        $this->timestamp = time();
        $token = $this->otpType === 'totp' ? $this->otp->at($this->timestamp) : $this->otp->at($this->otp->getCounter());

        return $token;
    }


    /**
     * Increment the hotp counter by 1
     * @return string The generated token
     */
    public function increaseHotpCounter() : void
    {
        if( $this->otpType === 'hotp' ) {
            $this->hotpCounter = $this->hotpCounter + 1;
            $this->refreshUri();
        }
    }


    /**
     * Populate the OTP sub-object wih the model URI
     *
     */
    private function populateFromUri($uri)
    {
        try {

            $this->otp = Factory::loadFromProvisioningUri($uri);

            // Account and Service values should be already recorded in the db so we set them
            // only when db has no value
            if( !$this->service ) { $this->service = $this->otp->getIssuer(); }
            if( !$this->account ) { $this->account = $this->otp->getLabel(); }

        }
        catch (\Exception $e) {
            throw new \App\Exceptions\InvalidOtpParameterException;
        }
    }


    /**
     * Update the uri attribute using the OTP object
     * @return void
     */
    private function refreshUri() : void
    {
        $this->uri = urldecode($this->otp->getProvisioningUri());
    }


    /**
     * Get icon attribute
     *
     * @param  string  $value
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getIconAttribute($value)
    {
        // Return an empty string if the corresponding resource does not exist on storage
        if (\App::environment('testing') == false) {
            if( !Storage::exists('public/icons/' . $value) ) {

                return '';
            }
        }

        return $value;
    }


    /**
     * Icon attribute setter
     *
     * @param  string  $value
     * @return string
     * 
     * @codeCoverageIgnore
     */
    public function setIconAttribute($value)
    {
        // Prevent setting a missing icon
        if( !Storage::exists('public/icons/' . $value) && \App::environment('testing') == false ) {

            $this->attributes['icon'] = '';
        }
        else {

            $this->attributes['icon'] = $value;
        }
    }
     

    /**
     * Get uri attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getUriAttribute($value)
    {
        // Decipher when needed
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
     * Set uri attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setUriAttribute($value)
    {
        // An uri contains all expected data to define an OTP object.
        // So we populate the model instance at every uri definition
        $this->populateFromUri($value);

        // Encrypt if needed
        $this->attributes['uri'] = Options::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }


    /**
     * Get account attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getAccountAttribute($value)
    {
        // Decipher when needed
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
     * Set account account
     *
     * @param  string  $value
     * @return void
     */
    public function setAccountAttribute($value)
    {
        // Encrypt when needed
        $this->attributes['account'] = Options::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }


    /**
     * Get IsConsistent attribute
     *
     * @return bool
     *
     */
    public function getIsConsistentAttribute()
    {
        return $this->uri === '*encrypted*' || $this->account === '*encrypted*' || $this->badUri ? false : true;
    }


    /**
     * get totpTimestamp attribute
     * 
     * @return int The timestamp
     */
    public function getTotpTimestampAttribute()
    {
        return $this->timestamp;
    }


    /**
     * get token attribute
     * 
     * @return string The token
     */
    public function getTokenAttribute() : string
    {
        return $this->generateToken();
    }


    /**
     * get otpType attribute
     *
     * @return string
     *
     */
    public function getOtpTypeAttribute()
    {
        if( isset($this->otp) ) {
            return get_class($this->otp) === 'OTPHP\TOTP' ? 'totp' : 'hotp';
        }
        else {
            return null;
        }
    }


    /**
     * get Secret attribute
     *
     * @return string
     *
     */
    public function getSecretAttribute()
    {
        return isset($this->otp) ? $this->otp->getSecret() : null;
    }


    /**
     * get algorithm attribute
     *
     * @return string
     *
     */
    public function getAlgorithmAttribute()
    {
        return isset($this->otp) ? $this->otp->getDigest() : null; // default is SHA1
    }


    /**
     * get Digits attribute
     *
     * @return string
     *
     */
    public function getDigitsAttribute()
    {
        return isset($this->otp) ? $this->otp->getDigits() : null;  // Default is 6
    }


    /**
     * get totpPeriod attribute
     *
     * @return string
     *
     */
    public function getTotpPeriodAttribute()
    {
        return $this->otpType === 'totp' ? $this->otp->getPeriod() : null;    // Default is 30
    }


    /**
     * get HotpCounter attribute
     *
     * @return string
     *
     */
    public function getHotpCounterAttribute()
    {
        return isset($this->otp) && $this->otpType === 'hotp' ? $this->otp->getCounter() : null;    // Default is 0
    }


    /**
     * set HotpCounter attribute
     *
     * @return string
     *
     */
    public function setHotpCounterAttribute($value)
    {
        $this->otp->setParameter( 'counter', $this->otp->getcounter() + 1 );
    }


    /**
     * get Image parameter attribute
     *
     * @return string
     *
     */
    public function getImageLinkAttribute()
    {
        if( isset($this->otp) ) {
            return $this->otp->hasParameter('image') ? $this->otp->getParameter('image') : null;
        }
        else {
            return false;
        }
    }

}