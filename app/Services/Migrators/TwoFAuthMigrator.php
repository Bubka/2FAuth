<?php

namespace App\Services\Migrators;

use App\Exceptions\InvalidMigrationDataException;
use App\Models\TwoFAccount;
use App\Services\IconService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class TwoFAuthMigrator extends Migrator
{
    // {
    //     "app": "2fauth_v3.4.1",
    //     "schema": 1,
    //     "datetime": "2022-12-14T14:53:06.173939Z",
    //     "data":
    //     [
    //         {
    //             "otp_type": "totp",
    //             "account": "cwxcwxc",
    //             "service": "wcxwxcwx",
    //             "icon": null,
    //             "icon_mime": null,
    //             "icon_file": null,
    //             "secret": "EEEE====",
    //             "digits": 6,
    //             "algorithm": "sha1",
    //             "period": 30,
    //             "counter": null,
    //             "legacy_uri": "otpauth://totp/wcxwxcwx%3Acwxcwxc?issuer=wcxwxcwx&secret=EEEE"
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
        $iconService = App::make(IconService::class);
        $json        = json_decode(htmlspecialchars_decode($migrationPayload), true);

        if (is_null($json)) {
            Log::error('2FAuth JSON migration data cannot be read');
            throw new InvalidMigrationDataException('2FAuth');
        }

        $twofaccounts = [];

        foreach ($json['data'] as $key => $otp_parameters) {
            $parameters               = [];
            $parameters['otp_type']   = $otp_parameters['otp_type'];
            $parameters['service']    = $otp_parameters['service'];
            $parameters['account']    = $otp_parameters['account'];
            $parameters['secret']     = $this->padToValidBase32Secret($otp_parameters['secret']);
            $parameters['algorithm']  = $otp_parameters['algorithm'] ?? null;
            $parameters['digits']     = $otp_parameters['digits'] ?? null;
            $parameters['legacy_uri'] = $otp_parameters['legacy_uri'];
            $parameters['counter']    = strtolower($parameters['otp_type']) === 'hotp' && $otp_parameters['counter'] > 0
                ? $otp_parameters['counter']
                : null;
            $parameters['period'] = strtolower($parameters['otp_type']) === 'totp' && $otp_parameters['period'] > 0
                ? $otp_parameters['period']
                : null;

            try {
                if (Arr::has($otp_parameters, 'icon_file') && Arr::has($otp_parameters, 'icon_mime')) {
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

                        case 'image/bmp':
                            $parameters['iconExt'] = 'bmp';
                            break;

                        case 'image/x-ms-bmp':
                            $parameters['iconExt'] = 'bmp';
                            break;

                        case 'image/webp':
                            $parameters['iconExt'] = 'webp';
                            break;

                        default:
                            throw new \Exception;
                    }
                    $parameters['icon_file'] = base64_decode($otp_parameters['icon_file']);
                }
            } catch (\Exception) {
                // we do nothing
            }

            try {
                $twofaccounts[$key] = new TwoFAccount;
                $twofaccounts[$key]->fillWithOtpParameters($parameters, Arr::has($parameters, 'iconExt'));
                if (Arr::has($parameters, 'iconExt')) {
                    $twofaccounts[$key]->icon = $iconService->buildFromResource($parameters['icon_file'], $parameters['iconExt']);
                }
            } catch (\Exception $exception) {
                Log::error(sprintf('Cannot instanciate a TwoFAccount object with 2FAS imported item #%s', $key));
                Log::debug($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount           = new TwoFAccount;
                $fakeAccount->id       = TwoFAccount::FAKE_ID;
                $fakeAccount->otp_type = $otp_parameters['otp']['tokenType'] ?? TwoFAccount::TOTP;
                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->account = $otp_parameters['otp']['account'] ?? __('message.invalid_account');
                $fakeAccount->service = $otp_parameters['name'] ?? __('message.invalid_service');
                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
            }
        }

        return collect($twofaccounts);
    }
}
