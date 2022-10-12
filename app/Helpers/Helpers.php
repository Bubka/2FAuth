<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers
{
    /**
     * Generate a unique filename
     * 
     * @param string $extension
     * @return string The filename
     */
    public static function getUniqueFilename(string $extension): string
    {
        return Str::random(40).'.'.$extension;
    }


    public static function cleanVersionNumber(?string $release): string|false
    {
        return preg_match('/([[0-9][0-9\.]*[0-9])/', $release, $version) ? $version[0] : false;
    }
}
