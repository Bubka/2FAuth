<?php

namespace App\Services\Migrators;

use App\Contracts\MigrationService;
use \Illuminate\Support\Collection;

class PlainTextMigrator implements MigrationService
{
    /**
     * Convert migration data to a 2FAccounts collection.
     *
     * @param  mixed  $migrationPayload
     * @return \Illuminate\Support\Collection The converted accounts
     */
    public function migrate(mixed $migrationPayload) : Collection
    {
        return Collect(['collected from plainTextMigrator']);
    }
}
