<?php

namespace App\Models;

use Exception;
use App\Events\TwoFAccountDeleted;
use Facades\App\Services\SettingService;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwoFAccount extends Model implements Sortable
{

    use SortableTrait, HasFactory;


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

        // static::deleted(function ($model) {
        //     Log::info(sprintf('TwoFAccount #%d deleted', $model->id));
        // });
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
        // Decipher when needed
        if ( SettingService::get('useEncryption') )
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
        return SettingService::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }

}