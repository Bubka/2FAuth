<?php

namespace App\Services\Migrators;

use Illuminate\Support\Collection;

abstract class Migrator
{
    /**
     * Convert migration data to a 2FAccounts collection.
     *
     * @return \Illuminate\Support\Collection<int|string, \App\Models\TwoFAccount> The converted accounts
     */
    abstract public function migrate(mixed $migrationPayload) : Collection;

    /**
     * Pad a string to 8 chars min
     *
     * @return string The padded string
     */
    protected function padToValidBase32Secret(string $string)
    {
        return str_pad($string, 8, '=');
    }
}
