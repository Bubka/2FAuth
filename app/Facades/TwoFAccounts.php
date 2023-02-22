<?php

namespace App\Facades;

use App\Services\TwoFAccountService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void withdraw(int|array|string $ids)
 * @method \Illuminate\Support\Collection<int|string, \App\Models\TwoFAccount> migrate(string $migrationPayload)
 * @method static \Illuminate\Support\Collection<int, \App\Models\TwoFAccount> export(int|array|string $ids)
 * @method static int delete(int|array|string $ids)
 *
 * @see \App\Services\TwoFAccountService
 */
class TwoFAccounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwoFAccountService::class;
    }
}
