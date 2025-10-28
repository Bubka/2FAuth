<?php

namespace App\Services\Migrators;

use App\Exceptions\InvalidMigrationDataException;
use App\Models\TwoFAccount;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlainTextMigrator extends Migrator
{
    /**
     * Convert migration data to a TwoFAccounts collection.
     *
     * @return \Illuminate\Support\Collection<int|string, \App\Models\TwoFAccount> The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection
    {
        $otpauthURIs = preg_split('~\R~', $migrationPayload);
        $otpauthURIs = Arr::where($otpauthURIs, function ($value, $key) {
            return Str::startsWith($value, ['otpauth://totp/', 'otpauth://hotp/', 'otpauth://steam/']);
        });

        if (count($otpauthURIs) < 1) {
            Log::error('No valid OtpAuth URI found in the migration');
            throw new InvalidMigrationDataException('migration');
        }

        foreach ($otpauthURIs as $key => $uri) {
            try {
                $twofaccounts[$key] = new TwoFAccount;
                $twofaccounts[$key]->fillWithURI($uri, str_starts_with($uri, 'otpauth://steam/'));
            } catch (\Exception $exception) {
                Log::error(sprintf('Cannot instanciate a TwoFAccount object with OTP parameters from imported item #%s', $key));
                Log::debug($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount           = new TwoFAccount;
                $fakeAccount->id       = TwoFAccount::FAKE_ID;
                $fakeAccount->otp_type = substr($uri, 10, 4);
                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->account = __('message.invalid_account');
                $fakeAccount->service = filter_input(INPUT_GET, 'issuer', FILTER_SANITIZE_ENCODED) ?? __('message.invalid_service');
                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
            }
        }

        return collect($twofaccounts);
    }
}
