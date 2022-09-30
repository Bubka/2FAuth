<?php

namespace App\Services;

use App\Models\TwoFAccount;
use Illuminate\Support\Facades\Log;

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
     * Explode a comma separated list of IDs to an array of IDs
     * 
     * @param int|array|string $ids
     */
    private static function commaSeparatedToArray($ids) : mixed
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
}