<?php

namespace App\Classes;

use OTPHP\TOTP;
use OTPHP\Factory;

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
        catch (InvalidArgumentException $exception) {
            return false;
        }

        $currentPosition = time();
        $PeriodCount = floor($currentPosition / 30); //nombre de période de 30s depuis T0
        $currentPeriodStartAt = $PeriodCount * 30;
        $currentPeriodendAt = $currentPeriodStartAt + 30;
        $positionInCurrentPeriod = $currentPosition - $currentPeriodStartAt;

        $totp = [
            'totp' => $otp->now(),
            'position' => $positionInCurrentPeriod
        ];

        return $totp;

    }


}
