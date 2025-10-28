<?php

namespace App\Services;

use App\Events\StoreIconsInDatabaseSettingChanged;
use App\Exceptions\DbEncryptionException;
use App\Models\Option;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SettingService
{
    /**
     * All settings
     *
     * @var Collection<string, mixed>
     */
    private Collection $settings;

    /**
     * Cache duration
     */
    private int $minutes = 10;

    /**
     * Name of the cache item where options are persisted
     */
    public const CACHE_ITEM_NAME = 'adminOptions';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settings = Cache::remember(self::CACHE_ITEM_NAME, now()->addMinutes($this->minutes), function () {
            self::build();

            return $this->settings;
        });
    }

    /**
     * Get a setting
     *
     * @param  string  $setting  A single setting name
     * @return mixed string|int|boolean|null
     */
    public function get($setting)
    {
        return $this->settings->get($setting);
    }

    /**
     * Get all settings
     *
     * @return Collection<string, mixed> the Settings collection
     */
    public function all() : Collection
    {
        return $this->settings;
    }

    /**
     * Set a setting
     *
     * @param  string  $setting  A single setting name
     * @param  string|int|bool  $value  The value for single setting
     */
    public function set($setting, $value) : void
    {
        // TODO: Move setEncryptionTo() logic to a dedicated class
        if ($setting === 'useEncryption') {
            $this->setEncryptionTo($value);
        }

        if ($setting === 'storeIconsInDatabase') {
            StoreIconsInDatabaseSettingChanged::dispatch($value);
        }

        Option::updateOrCreate(['key' => $setting], ['value' => $this->replaceBoolean($value)]);
        Log::notice(sprintf('App setting %s set to %s', var_export($setting, true), var_export($this->restoreType($value), true)));

        self::buildAndCache();
    }

    /**
     * Delete a setting
     *
     * @param  string  $name  The setting name
     */
    public function delete(string $name) : void
    {
        Option::where('key', $name)->delete();
        Log::notice(sprintf('App setting %s reset to default', var_export($name, true)));

        self::buildAndCache();
    }

    /**
     * Determine if the given setting has been edited
     *
     * @param  string  $key
     */
    public function isEdited($key) : bool
    {
        return DB::table('options')->where('key', $key)->exists();
    }

    /**
     * Set the settings collection
     *
     * @return void
     */
    private function build()
    {
        // Get a collection of saved options
        $options = DB::table('options')->pluck('value', 'key');
        $options->transform(function ($item, $key) {
            return $this->restoreType($item);
        });

        // Merge customized values with app default values
        $settings       = collect(config('2fauth.settings'))->merge($options); /** @phpstan-ignore-line */
        $this->settings = $settings;
    }

    /**
     * Build and cache the options collection
     *
     * @return void
     */
    private function buildAndCache()
    {
        self::build();
        Cache::put(self::CACHE_ITEM_NAME, $this->settings, now()->addMinutes($this->minutes));
    }

    /**
     * Replaces boolean by a patterned string as appstrack/laravel-options package does not support var type
     *
     * @return string
     */
    private function replaceBoolean(mixed $value)
    {
        return is_bool($value) ? '{{' . $value . '}}' : $value;
    }

    /**
     * Replaces patterned string that represent booleans with real booleans
     *
     * @return mixed
     */
    private function restoreType(mixed $value)
    {
        if (is_numeric($value)) {
            $value = is_float($value + 0) ? (float) $value : (int) $value;
        }

        if ($value === '{{}}') {
            return false;
        } elseif ($value === '{{1}}') {
            return true;
        } else {
            return $value;
        }
    }

    /**
     * Enable or Disable encryption of 2FAccounts sensible data
     *
     *
     * @throws DbEncryptionException Something failed, everything have been rolled back
     */
    private function setEncryptionTo(bool $state) : void
    {
        // We don't want the records to be encrypted/decrypted multiple successive times
        $isInUse = $this->get('useEncryption');

        if ($isInUse === ! $state) {
            if ($this->updateRecords($state)) {
                if ($state) {
                    Log::notice('Sensible data are now encrypted');
                } else {
                    Log::notice('Sensible data are now decrypted');
                }
            } else {
                Log::warning('Some data cannot be encrypted/decrypted, the useEncryption setting remain unchanged');
                throw new DbEncryptionException($state === true ? __('error.error_during_encryption') : __('error.error_during_decryption'));
            }
        }
    }

    /**
     * Encrypt/Decrypt accounts in database
     *
     * @param  bool  $encrypted  Whether the record should be encrypted or not
     * @return bool Whether the operation completed successfully
     */
    private function updateRecords(bool $encrypted) : bool
    {
        $success      = true;
        $twofaccounts = DB::table('twofaccounts')->get();
        $icons        = DB::table('icons')->get();

        $twofaccounts->each(function ($item, $key) use (&$success, $encrypted) {
            try {
                // encrypting a null value generate a hash which once decrypted gives an empty string.
                // As Service is nullable, we handle it only if the fiel contains a value
                if ($item->service) {
                    $item->service = $encrypted ? Crypt::encryptString($item->service) : Crypt::decryptString($item->service);
                }

                $item->legacy_uri = $encrypted ? Crypt::encryptString($item->legacy_uri) : Crypt::decryptString($item->legacy_uri);
                $item->account    = $encrypted ? Crypt::encryptString($item->account) : Crypt::decryptString($item->account);
                $item->secret     = $encrypted ? Crypt::encryptString($item->secret) : Crypt::decryptString($item->secret);
            } catch (Exception $ex) {
                $success = false;

                // Exit the each iteration
                return false;
            }
        });

        $icons->each(function ($item, $key) use (&$success, $encrypted) {
            try {
                $item->content = $encrypted ? Crypt::encryptString($item->content) : Crypt::decryptString($item->content);
            } catch (Exception $ex) {
                $success = false;

                // Exit the each iteration
                return false;
            }
        });

        if ($success) {
            // The whole collection has now its sensible data encrypted/decrypted
            // We update the db using a transaction that can rollback everything if an error occured
            DB::beginTransaction();

            try {
                $twofaccounts->each(function ($item, $key) {
                    DB::table('twofaccounts')
                        ->where('id', $item->id)
                        ->update([
                            'service'    => $item->service,
                            'legacy_uri' => $item->legacy_uri,
                            'account'    => $item->account,
                            'secret'     => $item->secret,
                        ]);
                });

                $icons->each(function ($item, $key) {
                    DB::table('icons')
                        ->where('name', $item->name)
                        ->update([
                            'content' => $item->content,
                        ]);
                });

                DB::commit();

                return true;
            }
            // @codeCoverageIgnoreStart
            catch (Throwable $ex) {
                DB::rollBack();

                return false;
            }
            // @codeCoverageIgnoreEnd
        } else {
            return false;
        }
    }
}
