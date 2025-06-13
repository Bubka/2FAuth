<?php

declare(strict_types=1);

namespace Tests\Data;

use App\Services\LogoLib\DashboardiconsLogoLib;
use App\Services\LogoLib\SelfhLogoLib;
use App\Services\LogoLib\TfaLogoLib;

final class CommonDataProvider
{
    const TFA_URL = 'https://raw.githubusercontent.com/2factorauth/twofactorauth/master/img/*';
    const SELFH_URL_ROOT = 'https://cdn.jsdelivr.net/gh/selfhst/icons/';
    const SELFH_URL = self::SELFH_URL_ROOT . '*';
    const DASHBOARDICONS_URL_ROOT = 'https://cdn.jsdelivr.net/gh/homarr-labs/dashboard-icons/';
    const DASHBOARDICONS_URL = self::DASHBOARDICONS_URL_ROOT . '*';

    public static function iconsCollectionProvider() : array
    {
        return [
            'TFA' => [[
                'name'  => 'tfa',
                'class' => TfaLogoLib::class,
                
            ]],
            'SELFH' => [[
                'name'  => 'selfh',
                'class' => SelfhLogoLib::class,
            ]],
            'DASHBOARDICONS' => [[
                'name'  => 'dashboardicons',
                'class' => DashboardiconsLogoLib::class,
            ]],
        ];
    }

}
