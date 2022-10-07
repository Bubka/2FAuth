<?php

namespace App\Factories;

use App\Services\Migrators\GoogleAuthMigrator;
use App\Services\Migrators\AegisMigrator;
use App\Services\Migrators\Migrator;
use App\Services\Migrators\PlainTextMigrator;
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
    public function create($migrationPayload) : Migrator
    {
        if ($this->isAegisJSON($migrationPayload)) {
            return App::make(AegisMigrator::class);
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
     * 
     */
    private function isGoogleAuth($migrationPayload) : bool
    {
        // - Google Auth migration URI : a string starting with otpauth-migration://offline?data= on a single line

        $lines = preg_split('~\R~', $migrationPayload, -1 , PREG_SPLIT_NO_EMPTY);

        if (!$lines || count($lines) != 1)
            return false;

        return preg_match('/^otpauth-migration:\/\/offline\?data=.+$/', $lines[0]) == 1;
    }


    /**
     * 
     */
    private function isPlainText($migrationPayload) : bool
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
     * 
     */
    private function isAegisJSON($migrationPayload) : mixed
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
                ));
            }
        }

        return false;
    }

}
