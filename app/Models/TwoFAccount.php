<?php

namespace App\Models;

use App\Events\TwoFAccountDeleted;
use App\Exceptions\InvalidOtpParameterException;
use App\Exceptions\InvalidSecretException;
use App\Exceptions\UndecipherableException;
use App\Exceptions\UnsupportedOtpTypeException;
use App\Facades\Icons;
use App\Helpers\Helpers;
use App\Models\Dto\HotpDto;
use App\Models\Dto\TotpDto;
use App\Models\Traits\CanEncryptField;
use Database\Factories\TwoFAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
 *
 * @method static \Database\Factories\TwoFAccountFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereAlgorithm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereLegacyUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereOtpType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereUserId($value)
 *
 * @mixin \Eloquent
 *
 * @property-read \App\Models\Icon|null $iconResource
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount orphans()
 */
class TwoFAccount extends Model implements Sortable
{
    /**
     * @use HasFactory<TwoFAccountFactory>
     */
    use CanEncryptField, HasFactory, SortableTrait;

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
     * @var array<int, string>
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
     * @var array<int, string>
     */
    public $appends = [];

    /**
     * The model's attributes.
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
    protected $casts = [
        'user_id' => 'integer',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User, $this>
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the relation between the icon resource and the model.
     *
     * @return HasOne<\App\Models\Icon, $this>
     */
    public function iconResource() : HasOne
    {
        return $this->hasOne(Icon::class, 'name', 'icon');
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
     * Get service attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getServiceAttribute($value)
    {
        return $this->decryptOrReturn($value);
    }

    /**
     * Set service attribute
     *
     * @param  string  $value
     * @return void
     */
    public function setServiceAttribute($value)
    {
        // Encrypt when needed
        $this->attributes['service'] = $value ? $this->encryptOrReturn($value) : $value;
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

            throw new UndecipherableException;
        }

        $this->initGenerator();

        try {
            if ($this->otp_type === self::HOTP) {
                $OtpDto           = new HotpDto;
                $OtpDto->otp_type = $this->otp_type;
                $counter          = $this->generator->getParameter('counter');
                $OtpDto->password = $this->generator->at($counter);
                $OtpDto->counter  = $this->counter = $counter + 1;

                // The updated HOTP counter must be saved to db for persisted account only
                if ($this->id) {
                    $this->save();
                }
            } else {
                $OtpDto               = new TotpDto;
                $OtpDto->otp_type     = $this->otp_type;
                $OtpDto->generated_at = $time ?: time();
                $expires_in           = $this->generator->expiresIn(); /** @phpstan-ignore-line - expiresIn() is in the TOTPInterface only */
                if ($this->otp_type === self::TOTP) {
                    $OtpDto->password      = $this->generator->at($OtpDto->generated_at);
                    $OtpDto->next_password = $this->generator->at($OtpDto->generated_at + $expires_in + 2);
                } else {
                    $OtpDto->password      = SteamTotp::getAuthCode(base64_encode(Base32::decodeUpper($this->secret)));
                    $OtpDto->next_password = SteamTotp::getAuthCode(base64_encode(Base32::decodeUpper($this->secret)), $expires_in + 2);
                }
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

        if (! $this->icon && ! $skipIconFetching && Auth::user()?->preferences['getOfficialIcons']) {
            $this->icon = Icons::buildFromOfficialLogo($this->service);
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

            // Hack for Microsoft corporate 2FAs for whom the Issuer query parameter != the Issuer aside the account
            $parsed_uri = \OTPHP\Url::fromString($uri);
            $pathChunks = explode(':', rawurldecode(mb_substr($parsed_uri->getPath(), 1)));
            $service    = $pathChunks[0];
            $issuer     = data_get($parsed_uri->getQuery(), 'issuer', $service);

            if (count($pathChunks) == 2 && strtolower($issuer) == 'microsoft' && strcasecmp($issuer, $service) != 0) {
                $newUri = str_replace($pathChunks[0] . ':', '', rawurldecode($uri));
                try {
                    $this->generator = Factory::loadFromProvisioningUri($newUri);
                    $this->generator->setLabel($service . '_' . $this->generator->getLabel());
                } catch (\Exception|\Throwable $ex) {
                    throw ValidationException::withMessages([
                        'uri' => __('validation.custom.uri.regex', ['attribute' => 'uri']),
                    ]);
                }
            } else {
                throw ValidationException::withMessages([
                    'uri' => __('validation.custom.uri.regex', ['attribute' => 'uri']),
                ]);
            }
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
            $this->icon = Icons::buildFromRemoteImage($this->generator->getParameter('image'));
        }

        if (! $this->icon && ! $skipIconFetching && Auth::user()?->preferences['getOfficialIcons']) {
            $this->icon = Icons::buildFromOfficialLogo($this->service);
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
                    throw new UnsupportedOtpTypeException;
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
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function buildSortQuery()
    {
        return static::query()->where('user_id', $this->user_id);
    }
}
