<?php

namespace App\Facades;

use App\Services\IconService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\IconService
 */
class Icons extends Facade
{
    protected static function getFacadeAccessor()
    {
        return IconService::class;
    }
}
