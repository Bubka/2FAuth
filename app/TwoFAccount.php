<?php

namespace App;

use Exception;
use OTPHP\TOTP;
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
    protected $appends = ['token', 'isConsistent', 'otpType', 'secret', 'algorithm', 'digits', 'totpPeriod', 'totpPosition', 'hotpCounter', 'imageLink'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['uri', 'secret', 'algorithm'];


    /**
     *  An OTP object from package Spomky-Labs/otphp
     *
     * @var OTPHP/TOTP || OTPHP/HOTP
     */
    protected $otp;


    /**
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model->populateFromUri();
        });

        static::saving(function ($model) {
            $model->refreshUri();
        });
        
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
     * Get IsConsistent attribute
     *
     * @return bool
     *
     */
    public function getIsConsistentAttribute()
    {
        return $this->uri === '*encrypted*' || $this->account === '*encrypted*' ? false : true;
    }


    /**
     * Populate some attributes of the model from an uri
     *
     * @param   $foreignUri an URI to parse
     * @return  Boolean wether or not the URI provided a valid OTP resource
     */
    public function populateFromUri(String $foreignUri = null) : bool
    {
        // No uri to parse
        if( !$this->uri && !$foreignUri ) {
            return false;
        }

        // The foreign uri is used in first place. This parameter is passed
        // when we need a TwoFAccount new object, for example after a qrcode upload
        // or for a preview
        $uri = $foreignUri ? $foreignUri : $this->uri;

        try {

            $this->otp = Factory::loadFromProvisioningUri($uri);

            // Account and service values are already recorded in the db so we set them
            // only when the uri used is a foreign uri, otherwise it would override
            // the db values
            if( $foreignUri ) {

                if(!$this->otp->getIssuer()) {
                    $this->otp->setIssuer($this->otp->getLabel());
                    $this->otp->setLabel('');
                }

                $this->service = $this->otp->getIssuer();
                $this->account = $this->otp->getLabel();
                $this->uri = $foreignUri;
            }

            return true;
        }
        catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'qrcode' => __('errors.response.no_valid_otp')
            ]);
        }

    }


    /**
     *  Populate attributes with direct values
     * @param  Array|array $attrib All attributes to be set
     */
    public function populate(Array $attrib = [])
    {
        // The Type and Secret attributes are mandatory
        // All other attributes have default value set by OTPHP

        if( strcasecmp($attrib['otpType'], 'totp') == 0 && strcasecmp($attrib['otpType'], 'hotp') == 0 ) {
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
            $secret = $attrib['secretIsBase32Encoded'] === 1 ? $attrib['secret'] : Encoding::base32EncodeUpper($attrib['secret']);

            $this->otp = strtolower($attrib['otpType']) === 'totp' ? TOTP::create($secret) : HOTP::create($secret);

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

            if (array_key_exists('digest', $attrib) && $attrib['algorithm'])
                { $this->otp->setParameter( 'digest', $attrib['algorithm'] ); }

            if (array_key_exists('totpPeriod', $attrib) && $attrib['totpPeriod'] && $attrib['otpType'] !== 'totp')
                { $this->otp->setParameter( 'period', (int) $attrib['totpPeriod'] ); }

            if (array_key_exists('hotpCounter', $attrib) && $attrib['hotpCounter'] && $attrib['otpType'] !== 'hotp')
                { $this->otp->setParameter( 'counter', (int) $attrib['hotpCounter'] ); }

            if (array_key_exists('imageLink', $attrib) && $attrib['imageLink'])
                { $this->otp->setParameter( 'image', $attrib['imageLink'] ); }

        }
        catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'qrcode' => __('errors.cannot_create_otp_with_those_parameters')
            ]);
        }

    }


    /**
     * Calculate where is now() in the totp current period
     * @return mixed The position
     */
    private function getTotpPosition()
    {
        // For memo :
        // $nextOtpAt = ($PeriodCount+1)*$period
        // $remainingTime = $nextOtpAt - time()
        if( $this->otpType === 'totp' ) {

            $currentPosition = time();
            $PeriodCount = floor($currentPosition / $this->totpPeriod); //nombre de période de x s depuis T0 (x=30 par défaut)
            $currentPeriodStartAt = $PeriodCount * $this->totpPeriod;
            $positionInCurrentPeriod = $currentPosition - $currentPeriodStartAt;

            return $positionInCurrentPeriod;
        }

        return null;
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
     * Generate a token which is valid at the current time (now)
     * @return string The generated token
     */
    public function generateToken() : string
    {
        return $this->otpType === 'totp' ? $this->otp->now() : $this->otp->at($this->otp->getCounter());
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
     * get token attribute
     * 
     * @return string The token
     */
    public function getTokenAttribute() : string
    {
        return $this->generateToken();
    }


    /**
     * get totpPosition attribute
     * 
     * @return int The position
     */
    public function getTotpPositionAttribute()
    {
        return $this->getTotpPosition();
    }


    /**
     * get OTP Type attribute
     *
     * @return string
     *
     */
    public function getOtpTypeAttribute()
    {
        return get_class($this->otp) === 'OTPHP\TOTP' ? 'totp' : 'hotp';
    }


    /**
     * get Secret attribute
     *
     * @return string
     *
     */
    public function getSecretAttribute()
    {
        return $this->otp->getSecret();
    }


    /**
     * get algorithm attribute
     *
     * @return string
     *
     */
    public function getAlgorithmAttribute()
    {
        return $this->otp->getDigest(); // default is SHA1
    }


    /**
     * get Digits attribute
     *
     * @return string
     *
     */
    public function getDigitsAttribute()
    {
        return $this->otp->getDigits();    // Default is 6
    }


    /**
     * get TOTP Period attribute
     *
     * @return string
     *
     */
    public function getTotpPeriodAttribute()
    {
        return $this->otpType === 'totp' ? $this->otp->getPeriod() : null;    // Default is 30
    }


    /**
     * get HOTP counter attribute
     *
     * @return string
     *
     */
    public function getHotpCounterAttribute()
    {
        return $this->otpType === 'hotp' ? $this->otp->getCounter() : null;    // Default is 0
    }


    /**
     * set HOTP counter attribute
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
        return $this->otp->hasParameter('image') ? $this->otp->getParameter('image') : null;
    }

}