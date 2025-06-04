<?php

namespace App\Services\LogoLib;

use App\Services\LogoLib\TfaLogoLib;
use Illuminate\Support\Manager;

class LogoLibManager extends Manager
{
    public function getDefaultDriver()
    {
        return 'tfa';
    }

    public function createTfaDriver() : TfaLogoLib
    {
        return new TfaLogoLib();
    }

    // public function createSelfhDriver()
    // {
    //     return new SelfhLogoLib();
    // }
}
