<?php

namespace App\Classes;

use Throwable;
use Exception;
use App\TwoFAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class DbProtection
{
    /**
     * Encrypt 2FA sensitive data
     * @return boolean
     */
    public static function enable() : bool
    {
        // All existing records have to be encrypted without exception.
        // This means that if any of the encryption failed we have to rollback
        // all records to their original value.
        
        $EncryptFailed = false;
        $twofaccounts = DB::table('twofaccounts')->get();

        $twofaccounts->each(function ($item, $key) use(&$EncryptFailed) {
            try {
                $item->uri = Crypt::encryptString($item->uri);
                $item->account = Crypt::encryptString($item->account);
            }
            catch (Exception $e) {
                $EncryptFailed = true;
                return false;
            }
        });
                
        if( $EncryptFailed ) {
            return false;
        }

        return self::tryUpdate($twofaccounts);
    }


    /**
     * Decrypt 2FA sensitive data
     * @return boolean
     */
    public static function disable() : bool
    {
        // All existing records have to be decrypted without exception.
        // This means that if any of the encryption failed we have to rollback
        // all records to their original value.
        
        $DecryptFailed = false;
        $EncryptedTwofaccounts = DB::table('twofaccounts')->get();

        $EncryptedTwofaccounts->each(function ($item, $key) use(&$DecryptFailed) {
            try {
                $item->uri = Crypt::decryptString($item->uri);
                $item->account = Crypt::decryptString($item->account);
            }
            catch (Exception $e) {
                $DecryptFailed = true;
                return false;
            }
        });
                
        if( $DecryptFailed ) {
            return false;
        }

        return DbProtection::tryUpdate($EncryptedTwofaccounts);
    }


    /**
     * Try to update all records of the collection
     * @param  Illuminate\Database\Eloquent\Collection $twofaccounts
     * @return boolean                  
     */
    private static function tryUpdate(\Illuminate\Support\Collection $twofaccounts) : bool
    {
        // The whole collection has its sensible data encrypted/decrypted, now we update the db
        // using a transaction to ensure rollback if an exception is thrown

        DB::beginTransaction();

        try {
            $twofaccounts->each(function ($item, $key) {
                DB::table('twofaccounts')
                    ->where('id', $item->id)
                    ->update([
                        'uri' => $item->uri,
                        'account' => $item->account
                    ]);
            });

            DB::commit();
        }
        // @codeCoverageIgnoreStart
        // Dont now how to fake that :(
        catch (Throwable $e) {
            DB::rollBack();

            return false;
        }
        // @codeCoverageIgnoreEnd

        return true;
    }

}