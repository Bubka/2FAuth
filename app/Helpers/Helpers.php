<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers
{
    /**
     * Generate a unique filename
     *
     * @param  string  $extension
     * @return string The filename
     */
    public static function getUniqueFilename(string $extension): string
    {
        return Str::random(40) . '.' . $extension;
    }

    /**
     * Clean a version number string
     *
     * @param  string|null  $release
     * @return string|false
     */
    public static function cleanVersionNumber(?string $release): string|false
    {
        // We use the regex for semver detection (see https://semver.org/)
        return preg_match('/(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-((?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+([0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?/', $release, $version) ? $version[0] : false;
    }

    /**
     * Format a string to comply with Base32 format
     *
     * @param  string  $str
     * @return string The filename
     */
    public static function PadToBase32Format(?string $str): string
    {
        return blank($str) ? '' : strtoupper(str_pad($str, (int) ceil(strlen($str) / 8) * 8, '='));
    }
}
