<?php

namespace App\Facades;

use App\Services\QrCodeService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\QrCodeService
 */
class QrCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return QrCodeService::class;
    }
}
