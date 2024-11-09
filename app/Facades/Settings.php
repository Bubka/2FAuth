<?php

namespace App\Facades;

use App\Services\SettingService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string|int|boolean|null get($setting)
 * @method static \Illuminate\Support\Collection<string, mixed> all()
 * @method static void set($setting, $value)
 * @method static void delete(string $name)
 * @method static bool isEdited($key)
 *
 * @see \App\Services\SettingService
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SettingService::class;
    }
}
