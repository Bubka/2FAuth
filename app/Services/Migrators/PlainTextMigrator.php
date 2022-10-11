<?php

namespace App\Services\Migrators;

use App\Services\Migrators\Migrator;
use Illuminate\Support\Collection;
use App\Models\TwoFAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exceptions\InvalidMigrationDataException;

class PlainTextMigrator extends Migrator
{

    /**
     * Convert migration data to a TwoFAccounts collection.
     *
     * @param  mixed  $migrationPayload
     * @return \Illuminate\Support\Collection The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection
    {
        $otpauthURIs = preg_split('~\R~', $migrationPayload);
        $otpauthURIs = Arr::where($otpauthURIs, function ($value, $key) {
            return Str::startsWith($value, ['otpauth://totp/', 'otpauth://hotp/']);
        });

        if (count($otpauthURIs) < 1) {
            Log::error('No valid OtpAuth URI found in the migration');
            throw new InvalidMigrationDataException('migration');
        }

        foreach ($otpauthURIs as $key => $uri) {

            try {
               $twofaccounts[$key] = new TwoFAccount;
               $twofaccounts[$key]->fillWithURI($uri);
            }
            catch (\Exception $exception) {

                Log::error(sprintf('Cannot instanciate a TwoFAccount object with OTP parameters from imported item #%s', $key));
                Log::error($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount = new TwoFAccount();
                $fakeAccount->id = -2;
                $fakeAccount->otp_type  = substr($uri, 10, 4);
                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->account   = __('twofaccounts.import.invalid_account');
                $fakeAccount->service   = filter_input(INPUT_GET, 'issuer', FILTER_SANITIZE_ENCODED) ?? __('twofaccounts.import.invalid_service');
                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret    = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
            }
        }

        return collect($twofaccounts);
    }
}
