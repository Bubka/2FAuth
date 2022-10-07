<?php

namespace App\Services\Migrators;

use Illuminate\Support\Collection;

abstract class Migrator
{
    /**
     * Convert migration data to a 2FAccounts collection.
     *
     * @param  mixed  $migrationPayload
     * @return \Illuminate\Support\Collection The converted accounts
     */
    abstract protected function migrate(mixed $migrationPayload) : Collection;

}
