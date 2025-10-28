<?php

namespace App\Services\Migrators;

use App\Exceptions\InvalidMigrationDataException;
use App\Models\TwoFAccount;
use App\Services\IconService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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
     * @return \Illuminate\Support\Collection<int|string, \App\Models\TwoFAccount> The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection
    {
        $iconService = App::make(IconService::class);
        $json        = json_decode(htmlspecialchars_decode($migrationPayload), true);

        if (is_null($json) || Arr::has($json, 'db.entries') == false) {
            Log::error('Aegis JSON migration data cannot be read');
            throw new InvalidMigrationDataException('Aegis');
        }

        $twofaccounts = [];

        foreach ($json['db']['entries'] as $key => $otp_parameters) {
            $parameters              = [];
            $parameters['otp_type']  = $otp_parameters['type'] == 'steam' ? TwoFAccount::STEAM_TOTP : $otp_parameters['type'];
            $parameters['service']   = $otp_parameters['issuer'];
            $parameters['account']   = $otp_parameters['name'] ?? $parameters['service'];
            $parameters['secret']    = $this->padToValidBase32Secret($otp_parameters['info']['secret']);
            $parameters['algorithm'] = $otp_parameters['info']['algo'] ?? null;
            $parameters['digits']    = $otp_parameters['info']['digits'] ?? null;
            $parameters['counter']   = $otp_parameters['info']['counter'] ?? null;
            $parameters['period']    = $otp_parameters['info']['period'] ?? null;

            try {
                // Aegis supports 3 image extensions for icons
                // (see https://github.com/beemdevelopment/Aegis/blob/3c10b234ea70715776a09e3d200cb6e806a43f83/docs/iconpacks.md)

                if (Arr::has($otp_parameters, 'icon') && Arr::has($otp_parameters, 'icon_mime')) {
                    switch ($otp_parameters['icon_mime']) {
                        case 'image/svg+xml':
                            $parameters['iconExt'] = 'svg';
                            break;

                        case 'image/png':
                            $parameters['iconExt'] = 'png';
                            break;

                        case 'image/jpeg':
                            $parameters['iconExt'] = 'jpg';
                            break;

                        default:
                            throw new \Exception;
                    }
                    $parameters['iconData'] = base64_decode($otp_parameters['icon']);
                }
            } catch (\Exception) {
                // we do nothing
            }

            try {
                $twofaccounts[$key] = new TwoFAccount;
                $twofaccounts[$key]->fillWithOtpParameters($parameters);
                if (Arr::has($parameters, 'iconExt') && Arr::has($parameters, 'iconData')) {
                    $twofaccounts[$key]->icon = $iconService->buildFromResource($parameters['iconData'], $parameters['iconExt']);
                }
            } catch (\Exception $exception) {
                Log::error(sprintf('Cannot instanciate a TwoFAccount object with OTP parameters from imported item #%s', $key));
                Log::debug($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount           = new TwoFAccount;
                $fakeAccount->id       = TwoFAccount::FAKE_ID;
                $fakeAccount->otp_type = $otp_parameters['type'] ?? TwoFAccount::TOTP;
                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->account = $otp_parameters['name'] ?? __('message.invalid_account');
                $fakeAccount->service = $otp_parameters['issuer'] ?? __('message.invalid_service');
                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
            }
        }

        return collect($twofaccounts);
    }
}
