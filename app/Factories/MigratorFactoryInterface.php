<?php

namespace App\Factories;

use App\Services\Migrators\Migrator;

interface MigratorFactoryInterface
{
    /**
     * Infer the type of migrator needed from a payload and create the migrator
     * 
     * @param string $migrationPayload The migration payload used to infer the migrator type
     * @return Migrator
     */
    public function create(string $migrationPayload) : Migrator;
}