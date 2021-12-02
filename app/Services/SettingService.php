<?php

namespace App\Services;

use Throwable;
use Exception;
use App\Models\Option;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Exceptions\DbEncryptionException;

class SettingService
{
    /**
     * Get a setting
     *
     * @param string|array $setting A single setting name or an associative array of name:value settings
     * @return mixed string|int|boolean|null
     */
    public function get(string $setting)
    {
        $options = $this->all();
        $value = $options->get($setting);

        return $value;
    }


    /**
     * Get all settings
     *
     * @return mixed Collection of settings
     */
    public function all() : Collection
    {
        // Get a collection of user saved options
        $userOptions = DB::table('options')->pluck('value', 'key');
        $userOptions->transform(function ($item, $key) {
            return $this->restoreType($item);
        });
        $userOptions = collect(config('2fauth.options'))->merge($userOptions);

        return $userOptions;
    }


    /**
     * Set a setting
     *
     * @param string|array $setting A single setting name or an associative array of name:value settings
     * @param string|int|boolean|null $value The value for single setting
     */
    public function set($setting, $value = null) : void
    {
        $settings = is_array($setting) ? $setting : [$setting => $value];

        foreach ($settings as $setting => $value) {
            if( $setting === 'useEncryption')
            {
                $this->setEncryptionTo($value);
            }

            $settings[$setting] = $this->replaceBoolean($value);
        }

        foreach ($settings as $setting => $value) {
            Option::updateOrCreate(['key' => $setting], ['value' => $value]);
            Log::info(sprintf('Setting %s is now %s', var_export($setting, true), var_export($this->restoreType($value), true)));
        }
    }


    /**
     * Delete a setting
     *
     * @param string $name The setting name
     */
    public function delete(string $name) : void
    {
        Option::where('key', $name)->delete();
        Log::info(sprintf('Setting %s deleted', var_export($name, true)));
    }
    

    /**
     * Replaces boolean by a patterned string as appstrack/laravel-options package does not support var type
     * 
     * @param \Illuminate\Support\Collection $settings
     * @return \Illuminate\Support\Collection
     */
    private function replaceBoolean($value)
    {
        return is_bool($value) ? '{{' . $value . '}}' : $value;
    }


    /**
     * Replaces patterned string that represent booleans with real booleans
     * 
     * @param \Illuminate\Support\Collection $settings
     * @return \Illuminate\Support\Collection
     */
    private function restoreType($value)
    {
        $value = is_numeric($value) ? (int) $value : $value;

        if( $value === '{{}}' ) {
            return false;
        }
        else if( $value === '{{1}}' ) {
            return true;
        }
        else {
            return $value;
        }
    }


    /**
     * Enable or Disable encryption of 2FAccounts sensible data
     * 
     * @return void
     * @throws DbEncryptionException Something failed, everything have been rolled back
     */
    private function setEncryptionTo(bool $state) : void
    {
        // We don't want the records to be encrypted/decrypted multiple successive times
        $isInUse = $this->get('useEncryption');

        if ($isInUse === !$state) {
            if ($this->updateRecords($state)) {
                if ($state) {
                    Log::notice('Sensible data are now encrypted');
                }
                else Log::notice('Sensible data are now decrypted');
            }
            else {
                Log::warning('Some data cannot be encrypted/decrypted, the useEncryption setting remain unchanged');
                throw new DbEncryptionException($state === true ? __('errors.error_during_encryption') : __('errors.error_during_decryption'));
            }
        }
    }


    /**
     * Encrypt/Decrypt accounts in database
     * 
     * @param boolean $encrypted Whether the record should be encrypted or not
     * @return boolean Whether the operation completed successfully
     */
    private function updateRecords(bool $encrypted) : bool
    {        
        $success = true;
        $twofaccounts = DB::table('twofaccounts')->get();

        $twofaccounts->each(function ($item, $key) use(&$success, $encrypted) {
            try {
                $item->legacy_uri   = $encrypted ? Crypt::encryptString($item->legacy_uri)  : Crypt::decryptString($item->legacy_uri);
                $item->account      = $encrypted ? Crypt::encryptString($item->account)     : Crypt::decryptString($item->account);
                $item->secret       = $encrypted ? Crypt::encryptString($item->secret)      : Crypt::decryptString($item->secret);
            }
            catch (Exception $ex) {
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
                            'legacy_uri' => $item->legacy_uri,
                            'account'    => $item->account,
                            'secret'     => $item->secret
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
        }
        else return false;
    }
}