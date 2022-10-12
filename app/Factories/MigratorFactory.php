<?php

namespace App\Factories;

use App\Services\Migrators\GoogleAuthMigrator;
use App\Services\Migrators\AegisMigrator;
use App\Services\Migrators\Migrator;
use App\Services\Migrators\PlainTextMigrator;
use App\Services\Migrators\TwoFASMigrator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\UnsupportedMigrationException;
use App\Exceptions\EncryptedMigrationException;

class MigratorFactory implements MigratorFactoryInterface
{
    /**
     * Infer the type of migrator needed from a payload and create the migrator
     * 
     * @param string $migrationPayload The migration payload used to infer the migrator type
     * @return Migrator
     */
    public function create(string $migrationPayload) : Migrator
    {
        if ($this->isAegisJSON($migrationPayload)) {
            return App::make(AegisMigrator::class);
        }
        else if ($this->is2FASv2($migrationPayload)) {
            return App::make(TwoFASMigrator::class);
        }
        else if ($this->isGoogleAuth($migrationPayload)) {
            return App::make(GoogleAuthMigrator::class);
        }
        else if ($this->isPlainText($migrationPayload)) {
            return App::make(PlainTextMigrator::class);
        }
        else throw new UnsupportedMigrationException();

    }


    /**
     * Determine if a payload comes from Google Authenticator
     * 
     * @param string $migrationPayload The payload to analyse
     * @return bool
     */
    private function isGoogleAuth(string $migrationPayload) : bool
    {
        // - Google Auth migration URI : a string starting with otpauth-migration://offline?data= on a single line

        $lines = preg_split('~\R~', $migrationPayload, -1 , PREG_SPLIT_NO_EMPTY);

        if (!$lines || count($lines) != 1)
            return false;

        return preg_match('/^otpauth-migration:\/\/offline\?data=.+$/', $lines[0]) == 1;
    }


    /**
     * Determine if a payload is a plain text content
     * 
     * @param string $migrationPayload The payload to analyse
     * @return bool
     */
    private function isPlainText(string $migrationPayload) : bool
    {
        // - Plain text : one or more otpauth URIs (otpauth://[t|h]otp/...), one per line

        return Validator::make(
            preg_split('~\R~', $migrationPayload, -1 , PREG_SPLIT_NO_EMPTY),
            [
                '*' => 'regex:/^otpauth:\/\/[h,t]otp\//i',
            ]
        )->passes();
    }


    /**
     * Determine if a payload comes from Aegis Authenticator in JSON format
     * 
     * @param string $migrationPayload The payload to analyse
     * @return bool
     */
    private function isAegisJSON(string $migrationPayload) : mixed
    {
        // - Aegis JSON : is a JSON object with the key db.entries full of objects like
        //      {
        //          "type": "totp",
        //          "uuid": "5be1c189-240d-5fe1-930b-a78xb669zd86",
        //          "name": "John DOE",
        //          "issuer": "Facebook",
        //          "note": "",
        //          "icon": null,
        //          "info": {
        //              "secret": "A4GRFTVVRBGY7UIW",
        //              "algo": "SHA1",
        //              "digits": 6,
        //              "period": 30
        //          }
        //      }

        $json = json_decode($migrationPayload, true);

        if (Arr::has($json, 'db')) {
            if (is_string($json['db']) && is_array(Arr::get($json, 'header.slots'))) {
                throw new EncryptedMigrationException();
            }
            else {
                return count(Validator::validate(
                    $json,
                    [
                        'db.entries.*.type' => 'required',
                        'db.entries.*.name' => 'required',
                        'db.entries.*.issuer' => 'required',
                        'db.entries.*.info' => 'required'
                    ]
                )) > 0;
            }
        }

        return false;
    }


    /**
     * Determine if a payload comes from 2FAS Authenticator
     * 
     * @param string $migrationPayload The payload to analyse
     * @return bool
     */
    private function is2FASv2(string $migrationPayload) : mixed
    {
        // - 2FAS JSON : is a JSON object with the key 'schemaVersion' == 2 and a key 'services' full of objects like
        // {
        //     "secret": "A4GRFTVVRBGY7UIW",
        //     ...
        //     "otp":
        //     {
        //         "account": "John DOE",
        //         "digits": 6,
        //         "counter": 0,
        //         "period": 30,
        //         "algorithm": "SHA1",
        //         "tokenType": "TOTP"
        //     },
        //     "type": "ManuallyAdded",
        //     "name": "Facebook",
        //     "icon":
        //     {
        //         ...
        //     }
        // }

        $json = json_decode($migrationPayload, true);
        
        if (Arr::get($json, 'schemaVersion') == 2 && (Arr::has($json, 'services') || Arr::has($json, 'servicesEncrypted'))) {
            if (Arr::has($json, 'servicesEncrypted')) {
                throw new EncryptedMigrationException();
            }
            else {
                return count(Validator::validate(
                    $json,
                    [
                        'services.*.secret' => 'required',
                        'services.*.name' => 'required',
                        'services.*.otp' => 'required'
                    ]
                )) > 0;
            }
        }

        return false;
    }

}
