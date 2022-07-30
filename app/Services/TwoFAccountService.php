<?php

namespace App\Services;

use App\Models\TwoFAccount;
use App\Exceptions\InvalidGoogleAuthMigration;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use ParagonIE\ConstantTime\Base32;
use App\Protobuf\GAuthValueMapping;
use App\Protobuf\GoogleAuth\Payload;
use App\Protobuf\GoogleAuth\Payload\OtpType;
use App\Protobuf\GoogleAuth\Payload\Algorithm;
use App\Protobuf\GoogleAuth\Payload\DigitCount;

class TwoFAccountService
{

    /**
     * Withdraw one or more twofaccounts from their group
     * 
     * @param int|array|string $ids twofaccount ids to free
     */
    public static function withdraw($ids) : void
    {
        // $ids as string could be a comma-separated list of ids
        // so in this case we explode the string to an array
        $ids = self::commaSeparatedToArray($ids);

        // whereIn() expects an array
        $ids = is_array($ids) ? $ids : func_get_args();

        TwoFAccount::whereIn('id', $ids)
                    ->update(
                        ['group_id' => NULL]
                    );
        
        Log::info(sprintf('TwoFAccounts #%s withdrawn', implode(',#', $ids)));
    }


    /**
     * Delete one or more twofaccounts
     * 
     * @param int|array|string $ids twofaccount ids to delete
     * 
     * @return int The number of deleted
     */
    public static function delete($ids) : int
    {
        // $ids as string could be a comma-separated list of ids
        // so in this case we explode the string to an array
        $ids = self::commaSeparatedToArray($ids);
        Log::info(sprintf('Deletion of TwoFAccounts #%s requested', is_array($ids) ? implode(',#', $ids) : $ids ));
        $deleted = TwoFAccount::destroy($ids);

        return $deleted;
    }


    /**
     * Convert Google Authenticator migration URI to a set of TwoFAccount objects
     * 
     * @param string $migrationUri migration uri provided by Google Authenticator export feature
     * 
     * @return \Illuminate\Support\Collection The converted accounts
     */
    public static function convertMigrationFromGA($migrationUri) : Collection
    {
        try {
            $migrationData = base64_decode(urldecode(Str::replace('otpauth-migration://offline?data=', '', $migrationUri)));
            $protobuf = new Payload();
            $protobuf->mergeFromString($migrationData);
            $otpParameters = $protobuf->getOtpParameters();
        }
        catch (Exception $ex) {
            Log::error("Protobuf failed to get OTP parameters from provided migration URI");
            Log::error($ex->getMessage());

            throw new InvalidGoogleAuthMigration();
        }

        foreach ($otpParameters->getIterator() as $key => $otp_parameters) {

             try {
                $parameters['otp_type']     = GAuthValueMapping::OTP_TYPE[OtpType::name($otp_parameters->getType())];
                $parameters['service']      = $otp_parameters->getIssuer();
                $parameters['account']      = str_replace($parameters['service'].':', '', $otp_parameters->getName());
                $parameters['secret']       = Base32::encodeUpper($otp_parameters->getSecret());
                $parameters['algorithm']    = GAuthValueMapping::ALGORITHM[Algorithm::name($otp_parameters->getAlgorithm())];
                $parameters['digits']       = GAuthValueMapping::DIGIT_COUNT[DigitCount::name($otp_parameters->getDigits())];
                $parameters['counter']      = $parameters['otp_type'] === TwoFAccount::HOTP ? $otp_parameters->getCounter() : null;
                $parameters['period']       = $parameters['otp_type'] === TwoFAccount::TOTP ? $otp_parameters->getPeriod() : null;

                $twofaccounts[$key] = new TwoFAccount;
                $twofaccounts[$key]->fillWithOtpParameters($parameters);
             }
             catch (Exception $exception) {

                Log::error(sprintf('Cannot instanciate a TwoFAccount object with OTP parameters from imported item #%s', $key));
                Log::error($exception->getMessage());

                // The token failed to generate a valid account so we create a fake account to be returned.
                $fakeAccount = new TwoFAccount();
                $fakeAccount->id = -2;
                $fakeAccount->otp_type  = $fakeAccount::TOTP;
                // Only basic fields are filled to limit the risk of another exception.
                $fakeAccount->account   = $otp_parameters->getName();
                $fakeAccount->service   = $otp_parameters->getIssuer();
                // The secret field is used to pass the error, not very clean but will do the job for now.
                $fakeAccount->secret    = $exception->getMessage();

                $twofaccounts[$key] = $fakeAccount;
             }
        }

        return self::markAsDuplicate(collect($twofaccounts));

    }


    /**
     * 
     */
    private static function commaSeparatedToArray($ids)
    {
        if(is_string($ids))
        {
            $regex = "/^\d+(,{1}\d+)*$/";
            if (preg_match($regex, $ids)) {
                $ids = explode(',', $ids);
            }
        }
        
        return $ids;
    }


    /**
     * Return the given collection with items marked as Duplicates (using id=-1) if a similar record exists in database
     * 
     * @param \Illuminate\Support\Collection
     * @return \Illuminate\Support\Collection
     */
    private static function markAsDuplicate($twofaccounts) : Collection
    {
        $storage = TwoFAccount::all();

        $twofaccounts = $twofaccounts->map(function ($twofaccount, $key) use ($storage) {
            if ($storage->contains(function ($value, $key) use ($twofaccount) {
                return $value->secret == $twofaccount->secret
                    && $value->service == $twofaccount->service
                    && $value->account == $twofaccount->account
                    && $value->otp_type == $twofaccount->otp_type
                    && $value->digits == $twofaccount->digits
                    && $value->algorithm == $twofaccount->algorithm;
            })) {
                $twofaccount->id = -1;
            }

            return $twofaccount;
        });

        return $twofaccounts;
    }
}