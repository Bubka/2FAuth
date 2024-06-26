<?php

namespace Tests\Feature\Console;

use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class PurgeLogTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_purgeLog_completes()
    {
        $this->artisan('2fauth:purge-log')
            ->assertSuccessful();
    }

    #[Test]
    public function test_purgeLog_defaults_to_one_year()
    {
        $oneYearOldLog   = AuthLog::factory()->daysAgo(366)->for($this->user, 'authenticatable')->create();
        $sixMonthsOldLog = AuthLog::factory()->daysAgo(364)->for($this->user, 'authenticatable')->create();

        $this->artisan('2fauth:purge-log');

        $this->assertDatabaseHas('auth_logs', [
            'id' => $sixMonthsOldLog->id,
        ]);
        $this->assertDatabaseMissing('auth_logs', [
            'id' => $oneYearOldLog->id,
        ]);
    }

    #[Test]
    public function test_purgeLog_deletes_records_older_than_retention_time()
    {
        $retention = 180;
        config(['2fauth.config.authLogRetentionTime' => $retention]);
        $log = AuthLog::factory()->daysAgo($retention + 1)->for($this->user, 'authenticatable')->create();

        $this->artisan('2fauth:purge-log');

        $this->assertDatabaseMissing('auth_logs', [
            'id' => $log->id,
        ]);
    }

    #[Test]
    public function test_purgeLog_deletes_logout_only_records_older_than_retention_time()
    {
        $retention = 180;
        config(['2fauth.config.authLogRetentionTime' => $retention]);
        $log = AuthLog::factory()->logoutOnly()->for($this->user, 'authenticatable')->create();

        $this->travelTo(Carbon::now()->addDays($retention + 1));
        $this->artisan('2fauth:purge-log');

        $this->assertDatabaseMissing('auth_logs', [
            'id' => $log->id,
        ]);
    }

    #[Test]
    public function test_purgeLog_does_not_delete_records_younger_than_retention_time()
    {
        $retention = 180;
        config(['2fauth.config.authLogRetentionTime' => $retention]);
        $log = AuthLog::factory()->daysAgo($retention - 1)->for($this->user, 'authenticatable')->create();

        $this->artisan('2fauth:purge-log');

        $this->assertDatabaseHas('auth_logs', [
            'id' => $log->id,
        ]);
    }

    #[Test]
    #[DataProvider('provideInvalidConfig')]
    public function test_purgeLog_with_invalid_config_defaults_to_one_year($config)
    {
        config(['2fauth.config.authLogRetentionTime' => $config]);
        $oneYearOldLog   = AuthLog::factory()->daysAgo(366)->for($this->user, 'authenticatable')->create();
        $sixMonthsOldLog = AuthLog::factory()->daysAgo(364)->for($this->user, 'authenticatable')->create();

        $this->artisan('2fauth:purge-log');

        $this->assertDatabaseHas('auth_logs', [
            'id' => $sixMonthsOldLog->id,
        ]);
        $this->assertDatabaseMissing('auth_logs', [
            'id' => $oneYearOldLog->id,
        ]);
    }

    /**
     * Provide invalid config for validation test
     */
    public static function provideInvalidConfig() : array
    {
        return [
            'NULL' => [
                null,
            ],
            'EMPTY' => [
                '',
            ],
            'STRING' => [
                'ljhkjh',
            ],
        ];
    }
}
