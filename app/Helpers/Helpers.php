<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helpers
{
    /**
     * Generate a unique filename
     * 
     * @param string $ids twofaccount ids to delete
     * 
     * @return string The filename
     */
    public static function getUniqueFilename(string $extension): string
    {
        return Str::random(40).'.'.$extension;
    }
}
