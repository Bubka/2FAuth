<?php

namespace App\Models;

use App\Events\TwoFAccountDeleted;
use App\Exceptions\InvalidOtpParameterException;
use App\Exceptions\InvalidSecretException;
use App\Exceptions\UndecipherableException;
use App\Exceptions\UnsupportedOtpTypeException;
use App\Facades\Settings;
use App\Helpers\Helpers;
use App\Models\Dto\HotpDto;
use App\Models\Dto\TotpDto;
use App\Services\LogoService;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OTPHP\Factory;
use OTPHP\HOTP;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use SteamTotp\SteamTotp;

/**
 * App\Models\TwoFAccount
 *
 * @property int $id
 * @property string|null $service
 * @property string $legacy_uri
 * @property string $account
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $order_column
 * @property int|null $group_id
 * @property string $otp_type
 * @property string $secret
 * @property string $algorithm
 * @property int $digits
 * @property int|null $period
 * @property int|null $counter
 * @property int|null $user_id
 * @property-read \App\Models\User|null $user
 */
class TwoFAccount extends Model implements Sortable
{
    use SortableTrait, HasFactory;

    const TOTP = 'totp';

    const HOTP = 'hotp';

    const STEAM_TOTP = 'steamtotp';

    const SHA1 = 'sha1';

    const MD5 = 'md5';

    const SHA256 = 'sha256';

    const SHA512 = 'sha512';

    const DEFAULT_PERIOD = 30;

    const DEFAULT_COUNTER = 0;

    const DEFAULT_DIGITS = 6;

    const DEFAULT_ALGORITHM = self::SHA1;

    const DUPLICATE_ID = -1;

    const FAKE_ID = -2;

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
        'digits'    => 6,
        'algorithm' => self::SHA1,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
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
            if (! $twofaccount->legacy_uri) {
                $twofaccount->legacy_uri = $twofaccount->getURI();
            }
            if ($twofaccount->otp_type == TwoFAccount::TOTP && ! $twofaccount->period) {
                $twofaccount->period = TwoFAccount::DEFAULT_PERIOD;
            }
            if ($twofaccount->otp_type == TwoFAccount::HOTP && ! $twofaccount->counter) {
                $twofaccount->counter = TwoFAccount::DEFAULT_COUNTER;
            }
        });

        static::created(function (object $model) {
            Log::info(sprintf('TwoFAccount ID #%d created for user ID #%s', $model->id, $model->user_id));
        });
        static::updated(function (object $model) {
            Log::info(sprintf('TwoFAccount ID #%d updated by user ID #%s', $model->id, $model->user_id));
        });
        static::deleted(function (object $model) {
            Log::info(sprintf('TwoFAccount ID #%d deleted ', $model->id));
        });
    }

    /**
     * Settings for @spatie/eloquent-sortable package
     *
     * @var array
     */
    public $sortable = [
        'order_column_name'  => 'order_column',
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
     * Get the user that owns the twofaccount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, \App\Models\TwoFAccount>
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Scope a query to only include orphan (userless) accounts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<User>  $query
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    public function scopeOrphans($query)
    {
        return $query->where('user_id', null);
    }

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
     * @param  string  $value
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
     * @param  string  $value
     * @return void
     */
    public function setSecretAttribute($value)
    {
        // Encrypt when needed
        $this->attributes['secret'] = $this->encryptOrReturn(Helpers::PadToBase32Format($value));
    }

    /**
     * Set digits attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setDigitsAttribute($value)
    {
        $this->attributes['digits'] = ! $value ? 6 : $value;
    }

    /**
     * Set algorithm attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setAlgorithmAttribute($value)
    {
        $this->attributes['algorithm'] = ! $value ? self::SHA1 : strtolower($value);
    }

    /**
     * Set period attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setPeriodAttribute($value)
    {
        $this->attributes['period'] = ! $value && $this->otp_type === self::TOTP ? self::DEFAULT_PERIOD : $value;
    }

    /**
     * Set counter attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setCounterAttribute($value)
    {
        $this->attributes['counter'] = blank($value) && $this->otp_type === self::HOTP ? self::DEFAULT_COUNTER : $value;
    }

    /**
     * Returns a One-Time Password with its parameters
     *
     * @return TotpDto|HotpDto
     *
     * @throws InvalidSecretException The secret is not a valid base32 encoded string
     * @throws UndecipherableException The secret cannot be deciphered
     * @throws UnsupportedOtpTypeException The defined OTP type is not supported
     * @throws InvalidOtpParameterException One OTP parameter is invalid
     */
    public function getOTP(?int $time = null)
    {
        Log::info(sprintf('OTP requested for TwoFAccount (%s)', $this->id ? 'id:' . $this->id : 'preview'));

        // Early exit if the model has an undecipherable secret
        if (strtolower($this->secret) === __('errors.indecipherable')) {
            Log::error('Secret cannot be deciphered, OTP generation aborted');

            throw new UndecipherableException();
        }

        $this->initGenerator();

        try {
            if ($this->otp_type === self::HOTP) {
                $OtpDto           = new HotpDto();
                $OtpDto->otp_type = $this->otp_type;
                $counter          = $this->generator->getParameter('counter');
                $OtpDto->password = $this->generator->at($counter);
                $OtpDto->counter  = $this->counter = $counter + 1;

                // The updated HOTP counter must be saved to db for persisted account only
                if ($this->id) {
                    $this->save();
                }
            } else {
                $OtpDto               = new TotpDto();
                $OtpDto->otp_type     = $this->otp_type;
                $OtpDto->generated_at = $time ?: time();
                $OtpDto->password     = $this->otp_type === self::TOTP
                    ? $this->generator->at($OtpDto->generated_at)
                    : SteamTotp::getAuthCode(base64_encode(Base32::decodeUpper($this->secret)));
                $OtpDto->period = $this->period;
            }

            Log::info(sprintf('New OTP generated for TwoFAccount (%s)', $this->id ? 'id:' . $this->id : 'preview'));

            return $OtpDto;
        } catch (\Exception|\Throwable $ex) {
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
        $this->otp_type  = strtolower(Arr::get($parameters, 'otp_type'));
        $this->account   = Arr::get($parameters, 'account');
        $this->service   = Arr::get($parameters, 'service');
        $this->icon      = Arr::get($parameters, 'icon');
        $this->secret    = Arr::get($parameters, 'secret');
        $this->algorithm = strtolower(Arr::get($parameters, 'algorithm', self::SHA1));
        $this->digits    = Arr::get($parameters, 'digits', self::DEFAULT_DIGITS);
        $this->period    = Arr::get($parameters, 'period', $this->otp_type == self::TOTP ? self::DEFAULT_PERIOD : null);
        $this->counter   = Arr::get($parameters, 'counter', $this->otp_type == self::HOTP ? self::DEFAULT_COUNTER : null);

        $this->initGenerator();

        // The generator could have been initialized without a secret, in that case it generates one on the fly.
        // The secret attribute has thus to be updated
        $this->secret = $this->secret ?: $this->generator->getSecret();

        if ($this->otp_type === self::STEAM_TOTP || strtolower($this->service) === 'steam') {
            $this->enforceAsSteam();
        }

        if (! $this->icon && $this->shouldGetOfficialIcon() && ! $skipIconFetching) {
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
            $this->generator = Factory::loadFromProvisioningUri($isSteamTotp ? str_replace('otpauth://steam', 'otpauth://totp', $uri) : $uri);
        } catch (\Exception|\Throwable $ex) {
            throw ValidationException::withMessages([
                'uri' => __('validation.custom.uri.regex', ['attribute' => 'uri']),
            ]);
        }

        // As loadFromProvisioningUri() accept URI without label (nor account nor service) we check
        // that the account is set
        if (! $this->generator->getLabel()) {
            Log::error('URI passed to fillWithURI() must contain a label');

            throw ValidationException::withMessages([
                'label' => __('validation.custom.label.required'),
            ]);
        }

        $this->otp_type   = $this->getGeneratorOtpType();
        $this->account    = $this->generator->getLabel();
        $this->secret     = $this->generator->getSecret();
        $this->service    = $this->generator->getIssuer();
        $this->algorithm  = $this->generator->getDigest();
        $this->digits     = $this->generator->getDigits();
        $this->period     = $this->generator->hasParameter('period') ? $this->generator->getParameter('period') : null;
        $this->counter    = $this->generator->hasParameter('counter') ? $this->generator->getParameter('counter') : null;
        $this->legacy_uri = $uri;

        if ($isSteamTotp || strtolower($this->service) === 'steam') {
            $this->enforceAsSteam();
        }
        if ($this->generator->hasParameter('image')) {
            self::setIcon($this->generator->getParameter('image'));
        }

        if (! $this->icon && $this->shouldGetOfficialIcon() && ! $skipIconFetching) {
            $this->icon = $this->getDefaultIcon();
        }

        Log::info(sprintf('TwoFAccount filled with an URI'));

        return $this;
    }

    /**
     * Compare 2 TwoFAccounts
     */
    public function equals(self $other) : bool
    {
        return $this->service === $other->service &&
            $this->account === $other->account &&
            $this->icon === $other->icon &&
            $this->otp_type === $other->otp_type &&
            $this->secret === $other->secret &&
            $this->digits === $other->digits &&
            $this->algorithm === $other->algorithm &&
            $this->period === $other->period &&
            $this->counter === $other->counter;
    }

    /**
     * Sets model attributes to STEAM values
     */
    private function enforceAsSteam() : void
    {
        $this->otp_type  = self::STEAM_TOTP;
        $this->service   = 'Steam';
        $this->digits    = 5;
        $this->algorithm = self::SHA1;
        $this->period    = 30;

        Log::info(sprintf('TwoFAccount configured as Steam account'));
    }

    /**
     * Returns the OTP type of the instanciated OTP generator
     *
     * @return mixed
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
     *
     * @throws UnsupportedOtpTypeException The defined OTP type is not supported
     * @throws InvalidOtpParameterException One OTP parameter is invalid
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

            if ($this->service) {
                $this->generator->setIssuer($this->service);
            }
            if ($this->account) {
                $this->generator->setLabel($this->account);
            }
        } catch (UnsupportedOtpTypeException $exception) {
            Log::error(sprintf('%s is not an OTP type supported by the current generator', $this->otp_type));
            throw $exception;
        } catch (\Exception|\Throwable $exception) {
            throw new InvalidOtpParameterException($exception->getMessage());
        }
    }

    /**
     * Store and set the provided icon
     *
     * @param  \Psr\Http\Message\StreamInterface|\Illuminate\Http\File|\Illuminate\Http\UploadedFile|string|resource  $data
     * @param  string|null  $extension The resource extension, without the dot
     */
    public function setIcon($data, $extension = null) : void
    {
        $isRemoteData = Str::startsWith($data, ['http://', 'https://']) && Validator::make(
            [$data],
            ['url']
        )->passes();

        if ($isRemoteData) {
            $icon = $this->storeRemoteImageAsIcon($data);
        } else {
            $icon = $extension ? $this->storeFileDataAsIcon($data, $extension) : null;
        }

        $this->icon = $icon ?: $this->icon;
    }

    /**
     * Store img data as an icon file.
     *
     * @param  \Psr\Http\Message\StreamInterface|\Illuminate\Http\File|\Illuminate\Http\UploadedFile|string|resource  $content
     * @param  string  $extension The file extension, without the dot
     * @return string|null The filename of the stored icon or null if the operation fails
     */
    private function storeFileDataAsIcon($content, $extension) : string|null
    {
        $filename = self::getUniqueFilename($extension);

        if (Storage::disk('icons')->put($filename, $content)) {
            if (self::isValidIcon($filename, 'icons')) {
                Log::info(sprintf('Image "%s" successfully stored for import', $filename));

                return $filename;
            } else {
                Storage::disk('icons')->delete($filename);
            }
        }

        return null;
    }

    /**
     * Generate a unique filename
     *
     * @return string The filename
     */
    private function getUniqueFilename(string $extension) : string
    {
        return Str::random(40) . '.' . $extension;
    }

    /**
     * Validate a file is a valid image
     *
     * @param  string  $filename
     * @param  string  $disk
     */
    private function isValidIcon($filename, $disk) : bool
    {
        return in_array(Storage::disk($disk)->mimeType($filename), [
            'image/png',
            'image/jpeg',
            'image/webp',
            'image/bmp',
            'image/x-ms-bmp',
            'image/svg+xml',
        ]) && (Storage::disk($disk)->mimeType($filename) !== 'image/svg+xml' ? getimagesize(Storage::disk($disk)->path($filename)) : true);
    }

    /**
     * Gets the image resource pointed by the image url and store it as an icon
     *
     * @return string|null The filename of the stored icon or null if the operation fails
     */
    private function storeRemoteImageAsIcon(string $url) : string|null
    {
        try {
            $path_parts  = pathinfo($url);
            $newFilename = self::getUniqueFilename($path_parts['extension']);

            try {
                $response = Http::retry(3, 100)->get($url);

                if ($response->successful()) {
                    Storage::disk('imagesLink')->put($newFilename, $response->body());
                }
            } catch (\Exception $exception) {
                Log::error(sprintf('Cannot fetch imageLink at "%s"', $url));
            }

            if (self::isValidIcon($newFilename, 'imagesLink')) {
                // Should be a valid image, we move it to the icons disk
                if (Storage::disk('icons')->put($newFilename, Storage::disk('imagesLink')->get($newFilename))) {
                    Storage::disk('imagesLink')->delete($newFilename);
                }

                Log::info(sprintf('Icon file "%s" stored', $newFilename));
            } else {
                Storage::disk('imagesLink')->delete($newFilename);
                throw new \Exception('Unsupported mimeType or missing image on storage');
            }

            return Storage::disk('icons')->exists($newFilename) ? $newFilename : null;
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

        return $this->shouldGetOfficialIcon() ? $logoService->getIcon($this->service) : null;
    }

    /**
     * Tells if an official icon should be fetched
     */
    private function shouldGetOfficialIcon() : bool
    {
        return is_null($this->user)
            ? (bool) config('2fauth.preferences.getOfficialIcons')
            : (bool) $this->user->preferences['getOfficialIcons'];
    }

    /**
     * Returns an acceptable value
     */
    private function decryptOrReturn(mixed $value) : mixed
    {
        // Decipher when needed
        if (Settings::get('useEncryption') && $value) {
            try {
                return Crypt::decryptString($value);
            } catch (Exception $ex) {
                return __('errors.indecipherable');
            }
        } else {
            return $value;
        }
    }

    /**
     * Encrypt a value
     */
    private function encryptOrReturn(mixed $value) : mixed
    {
        // should be replaced by laravel 8 attribute encryption casting
        return Settings::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<TwoFAccount>
     */
    public function buildSortQuery()
    {
        return static::query()->where('user_id', $this->user_id);
    }
}
