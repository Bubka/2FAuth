<?php

namespace Tests\Feature\Console;

use Illuminate\Support\Facades\DB;
use Tests\FeatureTestCase;

/**
 * @covers \App\Console\Commands\CheckDbConnection
 */
class CheckDbConnectionTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function test_CheckDbConnection_ends_successfully()
    {
        $this->artisan('2fauth:check-db-connection')
            ->expectsOutput('This will return the name of the connected database, otherwise false')
            ->expectsOutput(DB::connection()->getDatabaseName())
            ->assertExitCode(1);
    }

    /**
     * @test
     */
    public function test_CheckDbConnection_without_db_returns_false()
    {
        DB::shouldReceive('connection', 'getPDO')
            ->andThrow(new \Exception());

        $this->artisan('2fauth:check-db-connection')
            ->assertExitCode(0);
    }
}
