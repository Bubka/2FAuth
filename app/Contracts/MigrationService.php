<?php

namespace App\Contracts;

use \Illuminate\Support\Collection;

interface MigrationService
{
    /**
     * Convert migration data to a 2FAccounts collection.
     *
     * @param  mixed  $migrationPayload
     * @return \Illuminate\Support\Collection The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection;
}
