<?php

namespace App\Facades;

use App\Services\SettingService;
use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SettingService::class;
    }
}