<?php

namespace App\Facades;

use App\Services\TwoFAccountService;
use Illuminate\Support\Facades\Facade;

class TwoFAccounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwoFAccountService::class;
    }
}