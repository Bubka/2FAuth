<?php

namespace Tests\Feature\Console;

use App\Console\Commands\CheckDbConnection;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;

/**
 * CheckDbConnectionTest test class
 */
#[CoversClass(CheckDbConnection::class)]
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
