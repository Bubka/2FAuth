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

        if( get_class($otp) === 'OTPHP\TOTP' ) {

            $currentPosition = time();
            $PeriodCount = floor($currentPosition / $otp->getPeriod()); //nombre de période de x s depuis T0 (x=30 par défaut)
            $currentPeriodStartAt = $PeriodCount * $otp->getPeriod();
            $positionInCurrentPeriod = $currentPosition - $currentPeriodStartAt;

            // For memo :
            // $nextOtpAt = ($PeriodCount+1)*$period
            // $remainingTime = $nextOtpAt - time()

            return $totp = [
                'otp' => $otp->now(),
                'position' => $positionInCurrentPeriod
            ];
        }
        else {
            // It's a HOTP
            $hotp = [
                'otp' => $otp->at($otp->getCounter()),
                'counter' => $otp->getCounter(),
            ];

            // now we update the counter for next code
            $otp->setParameter( 'counter', $otp->getcounter() + 1 );

            $twofaccount = \App\TwoFAccount::where('uri', $uri)->first();
            $twofaccount->uri = $otp->getProvisioningUri();
            $twofaccount->save();

            return $hotp;
        }

        

    }


}
