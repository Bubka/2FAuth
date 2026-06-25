<?php

namespace App\Facades;

use App\Services\GroupService;
use Illuminate\Support\Facades\Facade;

/**
 * @see GroupService
 */
class Groups extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GroupService::class;
    }
}
