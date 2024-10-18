<?php

namespace App\Facades;

use App\Services\IconStoreService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\IconStoreService
 */
class IconStore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return IconStoreService::class;
    }
}
