<?php

namespace App\Models;

use Exception;
use App\Services\LogoService;
use App\Facades\Settings;
use App\Models\Dto\TotpDto;
use App\Models\Dto\HotpDto;
use App\Events\TwoFAccountDeleted;
use App\Exceptions\InvalidSecretException;
use App\Exceptions\InvalidOtpParameterException;
use App\Exceptions\UnsupportedOtpTypeException;
use App\Exceptions\UndecipherableException;
use Illuminate\Validation\ValidationException;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use OTPHP\TOTP;
use OTPHP\HOTP;
use OTPHP\Factory;
use SteamTotp\SteamTotp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ParagonIE\ConstantTime\Base32;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class TwoFAccount extends Model implements Sortable
{

    use SortableTrait, HasFactory;

    const TOTP       = 'totp';
    const HOTP       = 'hotp';
    const STEAM_TOTP = 'steamtotp';

    const SHA1       = 'sha1';
    const MD5        = 'md5';
    const SHA256     = 'sha256';
    const SHA512     = 'sha512';
    
    const DEFAULT_PERIOD = 30;
    const DEFAULT_COUNTER = 0;
    const DEFAULT_DIGITS = 6;
    const DEFAULT_ALGORITHM = self::SHA1;

    private const IMAGELINK_STORAGE_PATH = 'imagesLink/';
    private const ICON_STORAGE_PATH      = 'public/icons/';


    /**
     * List of OTP types supported by 2FAuth
     */
    private array $generatorClassMap = [
        'OTPHP\TOTP' => self::TOTP,
        'OTPHP\HOTP' => self::HOTP,
    ];

    /**
     * model's array form.
     *
     * @var string[]
     */
    protected $fillable = [
        // 'service',
        // 'account',
        // 'otp_type',
        // 'digits',
        // 'secret',
        // 'algorithm',
        // 'counter',
        // 'period',
        // 'icon'
    ];


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
    * The model's default values for attributes.
    *
    * @var array
    */
    protected $attributes = [
        'digits' => 6,
        'algorithm' => self::SHA1,
    ];


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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => TwoFAccountDeleted::class,
    ];


    /**
     * Override The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (TwoFAccount $twofaccount) {
            if (!$twofaccount->legacy_uri) $twofaccount->legacy_uri = $twofaccount->getURI();
            if ($twofaccount->otp_type == TwoFAccount::TOTP && !$twofaccount->period) $twofaccount->period = TwoFAccount::DEFAULT_PERIOD;
            if ($twofaccount->otp_type == TwoFAccount::HOTP && !$twofaccount->counter) $twofaccount->counter = TwoFAccount::DEFAULT_COUNTER;
        });

        // static::deleted(function ($model) {
        //     Log::info(sprintf('TwoFAccount #%d deleted', $model->id));
        // });
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    // public function fill(array $attributes)
    // {
    //     parent::fill($attributes);

    //     if ($this->otp_type == self::TOTP && !$this->period) $this->period = self::DEFAULT_PERIOD;
    //     if ($this->otp_type == self::HOTP && !$this->counter) $this->counter = self::DEFAULT_COUNTER;

    //     return $this;
    // }


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
     * The OTP generator.
     * Instanciated as null to keep the model light
     *
     * @var \OTPHP\OTPInterface|null
     */
    protected $generator = null;


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
     * Set digits attribute
     *
     * @param string $value
     * @return void
     */
    public function setDigitsAttribute($value)
    {
        $this->attributes['digits'] = !$value ? 6 : $value;
    }


    /**
     * Set algorithm attribute
     *
     * @param string $value
     * @return void
     */
    public function setAlgorithmAttribute($value)
    {
        $this->attributes['algorithm'] = !$value ? self::SHA1 : $value;
    }


    /**
     * Set period attribute
     *
     * @param string $value
     * @return void
     */
    public function setPeriodAttribute($value)
    {
        $this->attributes['period'] = !$value && $this->otp_type === self::TOTP ? self::DEFAULT_PERIOD : $value;
    }


    /**
     * Set counter attribute
     *
     * @param string $value
     * @return void
     */
    public function setCounterAttribute($value)
    {
        $this->attributes['counter'] = is_null($value) && $this->otp_type === self::HOTP ? self::DEFAULT_COUNTER : $value;
    }


    /**
     * Returns a One-Time Password with its parameters
     * 
     * @throws InvalidSecretException The secret is not a valid base32 encoded string
     * @throws UndecipherableException The secret cannot be deciphered
     * @return TotpDto|HotpDto 
     */
    public function getOTP()
    {
        Log::info(sprintf('OTP requested for TwoFAccount (%s)', $this->id ? 'id:'.$this->id: 'preview'));

        // Early exit if the model has an undecipherable secret
        if (strtolower($this->secret) === __('errors.indecipherable')) {
            Log::error('Secret cannot be deciphered, OTP generation aborted');

            throw new UndecipherableException();
        }

        $this->initGenerator();
        
        try {
            if ( $this->otp_type === self::TOTP || $this->otp_type === self::STEAM_TOTP ) {

                $OtpDto = new TotpDto();
                $OtpDto->otp_type   = $this->otp_type;
                $OtpDto->generated_at   = time();
                $OtpDto->password       = $this->otp_type === self::TOTP
                                            ? $this->generator->at($OtpDto->generated_at)
                                            : SteamTotp::getAuthCode(base64_encode(Base32::decodeUpper($this->secret)));
                $OtpDto->period         = $this->period;
            }
            else if ( $this->otp_type === self::HOTP ) {

                $OtpDto = new HotpDto();
                $OtpDto->otp_type   = $this->otp_type;
                $counter = $this->generator->getCounter();
                $OtpDto->password   = $this->generator->at($counter);
                $OtpDto->counter    = $this->counter = $counter + 1;

            }

            Log::info(sprintf('New OTP generated for TwoFAccount (%s)', $this->id ? 'id:'.$this->id: 'preview'));
    
            return $OtpDto;

        }
        catch (\Exception|\Throwable $ex) {
            Log::error('An error occured, OTP generation aborted');
            // Currently a secret issue is the only possible exception thrown by OTPHP for this stack
            // so it is Ok to send the corresponding 2FAuth exception.
            // If the generator package change it could be necessary to throw a more generic exception.
            throw new InvalidSecretException($ex->getMessage());
        }
    }


    /**
     * Fill the model using an array of OTP parameters.
     * Missing parameters will be set with default values
     * 
     * @return $this
     */
    public function fillWithOtpParameters(array $parameters, bool $skipIconFetching = false)
    {
        $this->otp_type     = Arr::get($parameters, 'otp_type');
        $this->account      = Arr::get($parameters, 'account');
        $this->service      = Arr::get($parameters, 'service');
        $this->icon         = Arr::get($parameters, 'icon');
        $this->secret       = Arr::get($parameters, 'secret');
        $this->algorithm    = Arr::get($parameters, 'algorithm', self::SHA1);
        $this->digits       = Arr::get($parameters, 'digits', self::DEFAULT_DIGITS);
        $this->period       = Arr::get($parameters, 'period', $this->otp_type == self::TOTP ? self::DEFAULT_PERIOD : null);
        $this->counter      = Arr::get($parameters, 'counter', $this->otp_type == self::HOTP ? self::DEFAULT_COUNTER : null);

        $this->initGenerator();
        
        if ($this->otp_type === self::STEAM_TOTP || strtolower($this->service) === 'steam') {
            $this->enforceAsSteam();
        }

        if (!$this->icon && $skipIconFetching) {
            $this->icon = $this->getDefaultIcon();
        }

        if (!$this->icon && Settings::get('getOfficialIcons') && !$skipIconFetching) {
            $this->icon = $this->getDefaultIcon();
        } 

        Log::info(sprintf('TwoFAccount filled with OTP parameters'));

        return $this;
    }


    /**
     * Fill the model by parsing an otpauth URI
     * 
     * @return $this
     */
    public function fillWithURI(string $uri, bool $isSteamTotp = false, bool $skipIconFetching = false)
    {
        // First we instanciate the OTP generator
        try {
            $this->generator = Factory::loadFromProvisioningUri($uri);
        }
        catch (\Assert\AssertionFailedException|\Assert\InvalidArgumentException|\Exception|\Throwable $ex) {
            throw ValidationException::withMessages([
                'uri' => __('validation.custom.uri.regex', ['attribute' => 'uri'])
            ]);
        }

        // As loadFromProvisioningUri() accept URI without label (nor account nor service) we check
        // that the account is set
        if ( ! $this->generator->getLabel() ) {
            Log::error('URI passed to fillWithURI() must contain a label');

            throw ValidationException::withMessages([
                'label' => __('validation.custom.label.required')
            ]);
        }

        $this->otp_type     = $this->getGeneratorOtpType();
        $this->account      = $this->generator->getLabel();
        $this->secret       = $this->generator->getSecret();
        $this->service      = $this->generator->getIssuer();
        $this->algorithm    = $this->generator->getDigest();
        $this->digits       = $this->generator->getDigits();
        $this->period       = $this->generator->hasParameter('period') ? $this->generator->getParameter('period') : null;
        $this->counter      = $this->generator->hasParameter('counter') ? $this->generator->getParameter('counter') : null;
        $this->legacy_uri   = $uri;
        
        if ($isSteamTotp || strtolower($this->service) === 'steam') {
            $this->enforceAsSteam();
        }
        if ($this->generator->hasParameter('image')) {
            $this->icon = $this->storeImageAsIcon($this->generator->getParameter('image'));
        }

        if (!$this->icon && Settings::get('getOfficialIcons') && !$skipIconFetching) {
            $this->icon = $this->getDefaultIcon();
        }    

        Log::info(sprintf('TwoFAccount filled with an URI'));

        return $this;
    }


    /**
     * Sets model attributes to STEAM values
     */
    private function enforceAsSteam() : void
    {
        $this->otp_type  = self::STEAM_TOTP;
        $this->digits    = 5;
        $this->algorithm = self::SHA1;
        $this->period    = 30;
        
        Log::info(sprintf('TwoFAccount configured as Steam account'));
    }


    /**
     * Returns the OTP type of the instanciated OTP generator
     */
    private function getGeneratorOtpType()
    {
        return Arr::get($this->generatorClassMap, get_class($this->generator));
    }

    /**
     * Returns an otpauth URI built with model attribute values
     */
    public function getURI() : string
    {
        $this->initGenerator();

        return $this->generator->getProvisioningUri();
    }


    /**
     * Instanciates the OTP generator with model attribute values
     */
    private function initGenerator() : void
    {
        try {
            switch ($this->otp_type) {
                case self::TOTP:
                    $this->generator = TOTP::create(
                        $this->secret,
                        $this->period ?: self::DEFAULT_PERIOD,
                        $this->algorithm ?: self::DEFAULT_ALGORITHM,
                        $this->digits ?: self::DEFAULT_DIGITS
                    );
                    break;

                case self::STEAM_TOTP:
                    $this->generator = TOTP::create($this->secret, 30, self::SHA1, 5);
                    break;

                case self::HOTP:
                    $this->generator = HOTP::create(
                        $this->secret,
                        $this->counter ?: self::DEFAULT_COUNTER,
                        $this->algorithm ?: self::DEFAULT_ALGORITHM,
                        $this->digits ?: self::DEFAULT_DIGITS
                    );
                    break;
                
                default:
                    throw new UnsupportedOtpTypeException();
            }

            if ($this->service) $this->generator->setIssuer($this->service);
            if ($this->account) $this->generator->setLabel($this->account);
        }
        catch (UnsupportedOtpTypeException $exception) {
            Log::error(sprintf('%s is not an OTP type supported by the current generator', $this->otp_type));
            throw $exception;
        }
        catch (\Exception|\Throwable $exception) {
            throw new InvalidOtpParameterException($exception->getMessage());
        }
    }

    /**
     * Gets the image resource pointed by the image url and store it as an icon
     * 
     * @return string|null The filename of the stored icon or null if the operation fails
     */
    private function storeImageAsIcon(string $url)
    {
        try {
            $path_parts = pathinfo($url);
            $newFilename = Str::random(40).'.'.$path_parts['extension'];
            $imageFile = self::IMAGELINK_STORAGE_PATH . $newFilename;

            try {
                $response = Http::retry(3, 100)->get($url);
                
                if ($response->successful()) {
                    Storage::disk('imagesLink')->put($newFilename, $response->body());
                }
            }
            catch (\Exception $exception) {
                Log::error(sprintf('Cannot fetch imageLink at "%s"', $url));
            }

            if ( in_array(Storage::mimeType($imageFile), ['image/png', 'image/jpeg', 'image/webp', 'image/bmp']) 
                && getimagesize(storage_path() . '/app/' . $imageFile) )
            {
                // Should be a valid image, we move it to the icons disk
                if (Storage::disk('icons')->put($newFilename, Storage::disk('imagesLink')->get($newFilename))) {
                    Storage::disk('imagesLink')->delete($newFilename);
                }
                
                Log::info(sprintf('Icon file %s stored', $newFilename));
            }
            else {
                // @codeCoverageIgnoreStart
                Storage::disk('imagesLink')->delete($newFilename);
                throw new \Exception('Unsupported mimeType or missing image on storage');
                // @codeCoverageIgnoreEnd
            }
                
            return $newFilename;
        }
        // @codeCoverageIgnoreStart
        catch (\Exception|\Throwable $ex) {
            Log::error(sprintf('Icon storage failed: %s', $ex->getMessage()));
            return null;
        }
        // @codeCoverageIgnoreEnd
    }


    /**
     * Fetch a logo in the tfa directory and store it as a new stand alone icon
     * 
     * @return string|null The icon
     */
    private function getDefaultIcon()
    {
        $logoService = App::make(LogoService::class);

        return Settings::get('getOfficialIcons') ? $logoService->getIcon($this->service) : null;
    }


    /**
     * Returns an acceptable value
     */
    private function decryptOrReturn($value)
    {
        // Decipher when needed
        if ( Settings::get('useEncryption') && $value )
        {
            try {
                return Crypt::decryptString($value);
            }
            catch (Exception $ex) {
                return __('errors.indecipherable');
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
        // should be replaced by laravel 8 attribute encryption casting
        return Settings::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }

}