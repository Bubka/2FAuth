<?php

namespace Tests\Feature\Console;

use App\Console\Commands\CheckDbConnection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * CheckDbConnectionTest test class
 */
#[CoversClass(CheckDbConnection::class)]
class CheckDbConnectionTest extends FeatureTestCase
{
    #[Test]
    public function test_CheckDbConnection_ends_successfully()
    {
        $this->artisan('2fauth:check-db-connection')
            ->assertExitCode(1);
    }

    // #[Test]
    // public function test_CheckDbConnection_without_db_returns_false()
    // {
    //     DB::shouldReceive('connection', 'getPDO')
    //         ->andThrow(new \Exception());

    //     $this->artisan('2fauth:check-db-connection')
    //         ->assertExitCode(0);
    // }
}
