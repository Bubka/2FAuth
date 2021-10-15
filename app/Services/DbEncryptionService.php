<?php

namespace App\Services;

use Throwable;
use Exception;
use App\Exceptions\DbEncryptionException;
use App\Services\SettingServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DbEncryptionService
{

    /**
     * The Settings Service instance.
     */
    protected SettingServiceInterface $settingService;


    /**
     * Settings service constructor
     * 
     */
    public function __construct(SettingServiceInterface $SettingServiceInterface)
    {
        $this->settingService = $SettingServiceInterface;
    }


    /**
     * Enable or Disable encryption of 2FAccounts sensible data
     * 
     * @return void
     * @throws DbEncryptionException Something failed, everything have been rolledback
     */
    public function setTo(bool $state) : void
    {
        // We don't want the records to be encrypted/decrypted multiple successive times
        $isInUse = $this->settingService->get('useEncryption');

        if ($isInUse === !$state) {
            if ($this->updateRecords($state)) {
                $this->settingService->set('useEncryption', $state);

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
            // Dont now how to fake that :(
            catch (Throwable $ex) {
                DB::rollBack();
                return false;
            }
        }
        else return false;
    }
}