<?php

use App\Facades\Settings;
use App\Models\TwoFAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if ($this->dbIsEncrypted() && Schema::getColumnType('twofaccounts', 'service') === 'text') {
            $this->encryptServiceField();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->dbIsEncrypted()) {
            $this->decryptServiceField();
        }
    }

    /**
     * Return the encryption state of the database
     */
    protected function dbIsEncrypted() : bool
    {
        return Settings::get('useEncryption');
    }

    /**
     * Update the Service field of all twofaccounts records to its encrypted form
    */
    protected function encryptServiceField() : void
    {
        foreach (TwoFAccount::all() as $twofaccount) {
            Log::notice(sprintf('Migration: Trying to encrypt Service field for twofaccount with id #%s', $twofaccount->id));
        
            // We don't want to encrypt the Service field with a different APP_KEY
            // than the one used to encrypt the legacy_uri, account and secret fields, the
            // model would be inconsistent. 
            if ($twofaccount->legacy_uri === __('errors.indecipherable')) {
                Log::warning(sprintf('Migration: Service encryption failed for twofaccount with id #%s. The current APP_KEY cannot decipher already encrypted fields, encrypting the Service field with this key would lead to inconsistent model encryption', $twofaccount->id));
            }
            else {
                $rawServiceValue = $twofaccount->getRawOriginal('service');
                $twofaccount->service = $rawServiceValue;
                $twofaccount->save()
                    ? Log::notice(sprintf('Migration: Service encryption successful for twofaccount with id #%s',  $twofaccount->id))
                    : Log::warning(sprintf('Migration: Model saving failed for twofaccount with id #%s. The Service field was successfully encrypted but the change was not persisted to db', $twofaccount->id));
            }
        }
    }

    /**
     * Update the Service field of all twofaccounts records to a readable form
    */
    protected function decryptServiceField() : void
    {
        foreach (TwoFAccount::all() as $twofaccount) {
            Log::notice(sprintf('Migration rollback: Trying to decipher Service field for twofaccount with id #%s', $twofaccount->id));

            if ($twofaccount->legacy_uri === __('errors.indecipherable')) {
                Log::warning(sprintf('Migration rollback: Service decipherement failed for twofaccount with id #%s', $twofaccount->id));
            }
            else {
                DB::table('twofaccounts')
                    ->where('id', $twofaccount->id)
                    ->update([
                        'service' => $twofaccount->service,
                    ]);

                Log::notice(sprintf('Migration rollback: Service decipherement successful for twofaccount with id #%s',  $twofaccount->id));
            }
        }
    }
};
