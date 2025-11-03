<?php

namespace App\Services\Migrators;

use App\Exceptions\InvalidMigrationDataException;
use App\Models\TwoFAccount;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BitwardenMigrator extends Migrator
{
    // JSON from the bitwarden app:
    //
    // {
    //   "encrypted": false,
    //   "folders": [],
    //   "items": [
    //     {
    //       "passwordHistory": [],
    //       "revisionDate": "2025-10-28T15:27:43.012Z",
    //       "creationDate": "2024-01-03T12:30:50.043Z",
    //       "deletedDate": null,
    //       "archivedDate": null,
    //       "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
    //       "organizationId": null,
    //       "folderId": null,
    //       "type": 1,
    //       "reprompt": 0,
    //       "name": "Google",
    //       "notes": null,
    //       "favorite": false,
    //       "fields": [],
    //       "login": {
    //         "uris": [
    //           {
    //             "match": null,
    //             "uri": "http://localhost/login"
    //           }
    //         ],
    //         "username": "john.doe@gmail.com",
    //         "password": "password",
    //         "totp": "otpauth://totp/Google%3Ajohn%2Edoe?issuer=Google&secret=A5GRFTVVRBGY7UIW"
    //       },
    //       "collectionIds": null
    //     }
    //   ]
    // }
    //
    // JSON form the bitwarden authenticator mobile app:
    //
    // {
    //     "encrypted": false,
    //     "items": [
    //         {
    //             "favorite": false,
    //             "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
    //             "login": {
    //                 "totp": "otpauth://totp/Google:john%2Edoe%40gmail%2Ecom?secret=A5GRFTVVRBGY7UIW&issuer=Google&algorithm=SHA256&digits=7&period=60",
    //                 "username": "john.doe@gmail.com"
    //             },
    //             "name": "Google",
    //             "type": 1
    //         },
    //         {
    //             "favorite": false,
    //             "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
    //             "login": {
    //                 "totp": "steam://A5GRFTVVRBGY7UIW",
    //                 "username": "john.doe@gmail.com"
    //              },
    //              "name": "Steam",
    //              "type": 1
    //         }
    //     ]
    // }

    /**
     * Convert migration data to a TwoFAccounts collection.
     *
     * @return \Illuminate\Support\Collection<int|string, \App\Models\TwoFAccount> The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection
    {
        $json = json_decode(htmlspecialchars_decode($migrationPayload), true);

        if (is_null($json) || Arr::has($json, 'items') == false) {
            Log::error('Bitwarden JSON migration data cannot be read');
            throw new InvalidMigrationDataException('Bitwarden');
        }

        $twofaccounts = [];

        foreach ($json['items'] as $key => $otp_parameters) {
            $parameters = [];
            $uri        = $otp_parameters['login']['totp'];

            // For now Bitwarden only supports totp/steam, see https://bitwarden.com/help/integrated-authenticator/
            if ($isSteam = str_starts_with($uri, 'steam://')) {
                $parameters['otp_type'] = TwoFAccount::STEAM_TOTP;
                $parameters['secret']   = str_replace('steam://', '', $uri);
            }
            else {
                $parameters['otp_type']  = TwoFAccount::TOTP;
            }

            $parameters['service'] = $otp_parameters['name'];
            $parameters['account']   = $otp_parameters['login']['username'] ?? $parameters['service'];
            
            try {
                $twofaccounts[$key] = new TwoFAccount;

                if ($isSteam)
                {
                    $twofaccounts[$key]->fillWithOtpParameters($parameters);
                }
                else {
                    $twofaccounts[$key]->fillWithURI($uri);

                    // We override uri parameters with json explicit values in case the uri
                    // was unmodified by bitwarden
                    $twofaccounts[$key]['service'] = $parameters['service'];
                    $twofaccounts[$key]['account'] = $parameters['account'];
                }

            } catch (\Exception $exception) {
                Log::error(sprintf('Cannot instanciate a TwoFAccount object with Bitwarden imported item #%s', $key));
                Log::debug($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount           = new TwoFAccount;
                $fakeAccount->id       = TwoFAccount::FAKE_ID;
                $fakeAccount->otp_type = TwoFAccount::TOTP;

                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->service = $otp_parameters['name'] ?? __('message.invalid_service');
                $fakeAccount->account = $otp_parameters['login']['username'] ?? __('message.invalid_account');

                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
            }

        }

        return collect($twofaccounts);
    }
}
