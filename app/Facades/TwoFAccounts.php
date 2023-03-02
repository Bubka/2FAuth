<?php

namespace App\Facades;

use App\Services\TwoFAccountService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\TwoFAccountService
 */
class TwoFAccounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwoFAccountService::class;
    }
}
