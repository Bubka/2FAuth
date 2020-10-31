<?php

namespace App\Http\Controllers\Settings;

use Throwable;
use App\TwoFAccount;
use App\Classes\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;

class OptionController extends Controller
{


    /**
     * Get options
     * @return [type] [description]
     */
    public function index()
    {
        // Fetch all setting values
        $settings = Options::get();

        return response()->json(['settings' => $settings], 200);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function store(Request $request)
    {
        // The useEncryption option impacts the [existing] content of the database.
        // Encryption/Decryption of the data is done only if the user change the value of the option
        // to prevent successive encryption
        
        if( $request->useEncryption && !Options::get('useEncryption') ) {

            // user enabled the encryption
            if( !$this->encryptAccounts() ) {
                return response()->json(['message' => __('errors.error_during_encryption'), 'settings' => Options::get()], 422);
            }
        }
        else if( !$request->useEncryption && Options::get('useEncryption') ) {

            // user disabled the encryption
            if( !$this->decryptAccounts() ) {
                return response()->json(['message' => __('errors.error_during_decryption'), 'settings' => Options::get()], 422);
            }
        }

        // Store all options
        Options::store($request->all());

        return response()->json(['message' => __('settings.forms.setting_saved'), 'settings' => Options::get()], 200);
    }


    /**
     * Encrypt 2FA sensitive data
     * @return boolean
     */
    private function encryptAccounts() : bool
    {
        // All existing records have to be encrypted without exception.
        // This means that if any of the encryption failed we have to rollback
        // all records to their original value.
        
        $twofaccounts = TwoFAccount::all();

        $twofaccounts->each(function ($item, $key) {
            try {
                $item->uri = Crypt::encryptString($item->uri);
                $item->account = Crypt::encryptString($item->account);
            }
            catch (EncryptException $e) {
                return false;
            }
        });

        return $this->tryUpdate($twofaccounts);
    }


    /**
     * Decrypt 2FA sensitive data
     * @return boolean
     */
    private function decryptAccounts() : bool
    {
        // All existing records have to be decrypted without exception.
        // This means that if any of the encryption failed we have to rollback
        // all records to their original value.
        
        $twofaccounts = TwoFAccount::all();

        $twofaccounts->each(function ($item, $key) {
            try {
                $item->uri = Crypt::decryptString($item->uri);
                $item->account = Crypt::decryptString($item->account);
            }
            catch (DecryptException $e) {
                return false;
            }
        });

        return $this->tryUpdate($twofaccounts);
    }


    /**
     * Try to update all records of the collection
     * @param  Illuminate\Database\Eloquent\Collection $twofaccounts
     * @return boolean                  
     */
    private function tryUpdate(\Illuminate\Database\Eloquent\Collection $twofaccounts) : bool
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
        catch (Throwable $e) {
            DB::rollBack();

            return false;
        }

        return true;
    }

}
