<?php

namespace App\Services\Migrators;

use App\Services\Migrators\Migrator;
use Illuminate\Support\Collection;
use App\Models\TwoFAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Exceptions\InvalidMigrationDataException;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helpers;
use App\Facades\TwoFAccounts;

class AegisMigrator extends Migrator
{
    // Typical JSON structure of an Aegis export
    //
    // {
    //     "type": "totp",
    //     "uuid": "5be1c189-240d-5fe1-930b-a78xb669zd86",
    //     "name": "John DOE",
    //     "issuer": "Facebook",
    //     "note": "",
    //     "icon": "PHN2ZyB4bWxucz0ia[...]0KPC9zdmc+DQo=",
    //     "icon_mime": "image\/svg+xml",
    //     "info": {
    //         "secret": "A4GRFTVVRBGY7UIW",
    //         "algo": "SHA1",
    //         "digits": 6,
    //         "period": 30,
    //         "counter": 30
    //     }
    // }


    /**
     * Convert migration data to a TwoFAccounts collection.
     *
     * @param  mixed  $migrationPayload
     * @return \Illuminate\Support\Collection The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection
    {
        $json = json_decode(htmlspecialchars_decode($migrationPayload), true);

        if (is_null($json) || Arr::has($json, 'db.entries') == false) {
            Log::error('Aegis JSON migration data cannot be read');
            throw new InvalidMigrationDataException('Aegis');
        }

        $twofaccounts = array();

        foreach ($json['db']['entries'] as $key => $otp_parameters) {

            $parameters = array();
            $parameters['otp_type']     = $otp_parameters['type'] == 'steam' ? TwoFAccount::STEAM_TOTP : $otp_parameters['type'];
            $parameters['service']      = $otp_parameters['issuer'];
            $parameters['account']      = $otp_parameters['name'];
            $parameters['secret']       = $this->padToValidBase32Secret($otp_parameters['info']['secret']);
            $parameters['algorithm']    = $otp_parameters['info']['algo'];
            $parameters['digits']       = $otp_parameters['info']['digits'];
            $parameters['counter']      = $otp_parameters['info']['counter'] ?? null;
            $parameters['period']       = $otp_parameters['info']['period'] ?? null;

            try {
                // Aegis supports 3 image extensions for icons
                // (see https://github.com/beemdevelopment/Aegis/blob/3c10b234ea70715776a09e3d200cb6e806a43f83/docs/iconpacks.md)

                if (Arr::has($otp_parameters, 'icon') && Arr::has($otp_parameters, 'icon_mime')) {
                    switch ($otp_parameters['icon_mime']) {
                        case 'image/svg+xml':
                            $extension = 'svg';
                            break;

                        case 'image/png':
                            $extension = 'png';
                            break;

                        case 'image/jpeg':
                            $extension = 'jpg';
                            break;
                        
                        default:
                            throw new \Exception();
                    }

                    $filename = Helpers::getUniqueFilename($extension);

                    if (Storage::disk('icons')->put($filename, base64_decode($otp_parameters['icon']))) {
                        $parameters['icon'] = $filename;
                        Log::info(sprintf('Image %s successfully stored for import', $filename));
                    }
                }
            }
            catch (\Exception) {
                // we do nothing
            }

            try {
               $twofaccounts[$key] = new TwoFAccount;
               $twofaccounts[$key]->fillWithOtpParameters($parameters);
            }
            catch (\Exception $exception) {

                Log::error(sprintf('Cannot instanciate a TwoFAccount object with OTP parameters from imported item #%s', $key));
                Log::error($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount = new TwoFAccount();
                $fakeAccount->id = TwoFAccount::FAKE_ID;
                $fakeAccount->otp_type  = $otp_parameters['type'] ?? TwoFAccount::TOTP;
                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->account   = $otp_parameters['name'] ?? __('twofaccounts.import.invalid_account');
                $fakeAccount->service   = $otp_parameters['issuer'] ?? __('twofaccounts.import.invalid_service');
                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret    = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
            }
        }

        return collect($twofaccounts);
    }
}
