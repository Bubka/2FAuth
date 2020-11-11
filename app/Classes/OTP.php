<?php

namespace App\Classes;

use OTPHP\TOTP;
use OTPHP\Factory;
use Assert\AssertionFailedException;

class OTP
{

    /**
     * Generate a TOTP
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @param  Boolean $isPreview   Prevent updating storage in case of HOTP preview
     * @return an array that represent the totp code
     */
    public static function generate($twofaccount, $isPreview = false)
    {

        if( $twofaccount->otpType === 'totp' ) {

            $currentPosition = time();
            $PeriodCount = floor($currentPosition / $twofaccount->totpPeriod); //nombre de période de x s depuis T0 (x=30 par défaut)
            $currentPeriodStartAt = $PeriodCount * $twofaccount->totpPeriod;
            $positionInCurrentPeriod = $currentPosition - $currentPeriodStartAt;

            // For memo :
            // $nextOtpAt = ($PeriodCount+1)*$period
            // $remainingTime = $nextOtpAt - time()

            return $totp = [
                'otp' => $twofaccount->token(),
                'position' => $positionInCurrentPeriod
            ];
        }
        else {
            // It's a HOTP
            $hotp = [
                'otp' => $twofaccount->token(),
                'counter' => $twofaccount->hotpCounter
            ];

            // now we update the counter for the next OTP generation
            $twofaccount->increaseCounter();
            $hotp['nextUri'] = $twofaccount->uri;

            if( !$isPreview ) {
                $twofaccount->save();
            }

            return $hotp;
        }

    }

}
