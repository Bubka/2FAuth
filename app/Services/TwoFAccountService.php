<?php

namespace App\Services;

use App\Models\TwoFAccount;
use App\Factories\MigratorFactoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TwoFAccountService
{
    /**
     * @var MigratorFactoryInterface $migratorFactory The Migration service
     */
    protected $migratorFactory;


    /**
     * Constructor
     */
    public function __construct(MigratorFactoryInterface $migratorFactory)
    {
        $this->migratorFactory = $migratorFactory;
    }


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
     * Convert a migration payload to a set of TwoFAccount objects
     * 
     * @param string $migrationPayload Migration payload from 2FA apps export feature
     * @return \Illuminate\Support\Collection The converted accounts
     */
    public function migrate(string $migrationPayload) : Collection
    {
        $migrator = $this->migratorFactory->create($migrationPayload);
        $twofaccounts = $migrator->migrate($migrationPayload);

        return self::markAsDuplicate($twofaccounts);
    }


    /**
     * Delete one or more twofaccounts
     * 
     * @param int|array|string $ids twofaccount ids to delete
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
     * Return the given collection with items marked as Duplicates (using id=-1) if a similar record exists in database
     * 
     * @param \Illuminate\Support\Collection $twofaccounts
     * @return \Illuminate\Support\Collection
     */
    private static function markAsDuplicate(Collection $twofaccounts) : Collection
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
                $twofaccount->id = TwoFAccount::DUPLICATE_ID;
            }

            return $twofaccount;
        });

        return $twofaccounts;
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