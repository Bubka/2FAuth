<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Rollback and execute migrations for each test.
     */
    use LazilyRefreshDatabase;


    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        Artisan::call('passport:install',['--verbose' => 2]);
    }
}
