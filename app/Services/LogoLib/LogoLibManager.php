<?php

namespace App\Services\LogoLib;

use Illuminate\Support\Manager;

class LogoLibManager extends Manager
{
    public function getDefaultDriver()
    {
        return 'tfa';
    }

    public function createTfaDriver() : TfaLogoLib
    {
        return new TfaLogoLib;
    }

    public function createSelfhDriver() : SelfhLogoLib
    {
        return new SelfhLogoLib;
    }

    public function createDashboardiconsDriver() : DashboardiconsLogoLib
    {
        return new DashboardiconsLogoLib;
    }

    public function createStorageDriver() : StorageLogoLib
    {
        return new StorageLogoLib;
    }
}
