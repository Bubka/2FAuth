<?php

namespace App\Facades;

use App\Services\QrCodeService;
use Illuminate\Support\Facades\Facade;

class QrCode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return QrCodeService::class;
    }
}
