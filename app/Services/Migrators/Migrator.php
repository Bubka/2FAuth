<?php

namespace App\Services\Migrators;

use App\Models\TwoFAccount;
use \Illuminate\Support\Collection;

abstract class Migrator
{

    /**
     * Return the given collection with items marked as Duplicates (using id=-1) if a similar record exists in database
     * 
     * @param \Illuminate\Support\Collection $twofaccounts
     * @return \Illuminate\Support\Collection
     */
    protected static function markAsDuplicate(Collection $twofaccounts) : Collection
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
