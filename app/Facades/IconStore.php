<?php

namespace App\Facades;

use App\Services\IconStoreService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\IconStoreService\IconStoreService setDisk(string $diskName = 'icons')
 * @method static bool usesDatabase()
 * @method static void setDatabaseReplication(bool $usesDatabase)
 * @method static string|null get(string $name)
 * @method static string|false mimeType(string $name)
 * @method static bool clear()
 * @method static bool delete(array|string $names)
 * @method static bool store(string $name, string $content)
 * @method static bool exists(string $name)
 *
 * @see \App\Services\IconStoreService
 */
class IconStore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return IconStoreService::class;
    }
}
