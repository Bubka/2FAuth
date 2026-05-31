<?php

namespace Tests\Feature\Console;

use App\Models\OtpLog;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class PurgeOtpLogTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected TwoFAccount $twofaccount;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->twofaccount = TwoFAccount::factory()->create();
    }

    #[Test]
    public function test_purge_log_completes()
    {
        $this->artisan('2fauth:purge-otp-log')
            ->assertSuccessful();
    }

    #[Test]
    public function test_purge_otp_log_defaults_to_one_year()
    {
        $oneYearOldLog = OtpLog::factory()->daysAgo(366)
            ->for($this->user, 'requester')
            ->for($this->user, 'owner')
            ->for($this->twofaccount, 'twofaccount')
            ->create();

        $sixMonthsOldLog = OtpLog::factory()->daysAgo(364)
            ->for($this->user, 'requester')
            ->for($this->user, 'owner')
            ->for($this->twofaccount, 'twofaccount')
            ->create();

        $this->artisan('2fauth:purge-otp-log');

        $this->assertDatabaseHas('otp_logs', [
            'id' => $sixMonthsOldLog->id,
        ]);
        $this->assertDatabaseMissing('otp_logs', [
            'id' => $oneYearOldLog->id,
        ]);
    }

    #[Test]
    public function test_purge_otp_log_deletes_records_older_than_retention_time()
    {
        $retention = 180;
        config(['2fauth.config.otpLogRetentionTime' => $retention]);

        $log = OtpLog::factory()->daysAgo($retention + 1)
            ->for($this->user, 'requester')
            ->for($this->user, 'owner')
            ->for($this->twofaccount, 'twofaccount')
            ->create();

        $this->artisan('2fauth:purge-otp-log');

        $this->assertDatabaseMissing('otp_logs', [
            'id' => $log->id,
        ]);
    }

    #[Test]
    public function test_purge_otp_log_does_not_delete_records_younger_than_retention_time()
    {
        $retention = 180;
        config(['2fauth.config.otpLogRetentionTime' => $retention]);

        $log = OtpLog::factory()->daysAgo($retention - 1)
            ->for($this->user, 'requester')
            ->for($this->user, 'owner')
            ->for($this->twofaccount, 'twofaccount')
            ->create();

        $this->artisan('2fauth:purge-otp-log');

        $this->assertDatabaseHas('otp_logs', [
            'id' => $log->id,
        ]);
    }

    #[Test]
    #[DataProvider('provideInvalidConfig')]
    public function test_purge_otp_log_with_invalid_config_defaults_to_one_year(mixed $config)
    {
        config(['2fauth.config.otpLogRetentionTime' => $config]);

        $oneYearOldLog   = OtpLog::factory()->daysAgo(366)
            ->for($this->user, 'requester')
            ->for($this->user, 'owner')
            ->for($this->twofaccount, 'twofaccount')
            ->create();

        $sixMonthsOldLog = OtpLog::factory()->daysAgo(364)
            ->for($this->user, 'requester')
            ->for($this->user, 'owner')
            ->for($this->twofaccount, 'twofaccount')
            ->create();

        $this->artisan('2fauth:purge-otp-log');

        $this->assertDatabaseHas('otp_logs', [
            'id' => $sixMonthsOldLog->id,
        ]);
        $this->assertDatabaseMissing('otp_logs', [
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
