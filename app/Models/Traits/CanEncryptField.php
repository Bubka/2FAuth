<?php

namespace App\Models\Traits;

use App\Facades\Settings;
use Illuminate\Support\Facades\Crypt;

trait CanEncryptField
{
    /**
     * Returns an acceptable value
     */
    private function decryptOrReturn(mixed $value) : mixed
    {
        // Decipher when needed
        if (Settings::get('useEncryption') && $value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $ex) {
                return __('error.indecipherable');
            }
        } else {
            return $value;
        }
    }

    /**
     * Encrypt a value
     */
    private function encryptOrReturn(mixed $value) : mixed
    {
        // should be replaced by laravel 8 attribute encryption casting
        return Settings::get('useEncryption') ? Crypt::encryptString($value) : $value;
    }
}
