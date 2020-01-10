<?php

namespace App\Classes;

use OTPHP\TOTP;
use OTPHP\Factory;
use Assert\AssertionFailedException;

class TimedTOTP
{

    /**
     * Generate a TOTP
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return an array that represent the totp code
     */
    public static function get($uri)
    {
        
        try {
            $otp = Factory::loadFromProvisioningUri($uri);
        }
        catch (AssertionFailedException $exception) {
            return false;
        }

        $currentPosition = time();
        $PeriodCount = floor($currentPosition / $otp->getPeriod()); //nombre de période de x s depuis T0 (x=30 par défaut)
        $currentPeriodStartAt = $PeriodCount * $otp->getPeriod();
        $positionInCurrentPeriod = $currentPosition - $currentPeriodStartAt;

        // for memo :
        // $nextOtpAt = ($PeriodCount+1)*$period
        // $remainingTime = $nextOtpAt - time()

        $totp = [
            'totp' => $otp->now(),
            'position' => $positionInCurrentPeriod
        ];

        return $totp;

    }


}
