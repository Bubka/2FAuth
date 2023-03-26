<?php

namespace App\Helpers;

class Helpers
{
    /**
     * Clean a version number string
     */
    public static function cleanVersionNumber(?string $release) : string|false
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
    public static function PadToBase32Format(?string $str) : string
    {
        return blank($str) ? '' : strtoupper(str_pad($str, (int) ceil(strlen($str) / 8) * 8, '='));
    }

    /**
     * Identify comma separated list of values and explode it to an array of values
     *
     * @param  mixed  $ids
     */
    public static function commaSeparatedToArray($ids) : mixed
    {
        if (is_string($ids)) {
            $regex = "/^\d+(,{1}\d+)*$/";
            if (preg_match($regex, $ids)) {
                $ids = explode(',', $ids);
            }
        }

        return $ids;
    }
}
