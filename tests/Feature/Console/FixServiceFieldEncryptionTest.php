<?php

namespace Tests\Feature\Console;

use App\Console\Commands\Maintenance\FixServiceFieldEncryption;
use App\Facades\Settings;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * FixServiceFieldEncryptionTest test class
 */
#[CoversClass(FixServiceFieldEncryption::class)]
class FixServiceFieldEncryptionTest extends FeatureTestCase
{
    /**
     * The name of the migration that changed the data this command will try to fix
     */
    protected string $relatedMigration = '2024_08_08_133136_encrypt_twofaccount_service_field';

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var string
     */
    protected $command = '2fauth:fix-service-encryption';

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_it_does_not_run_if_migration_has_not_been_run()
    {
        DB::table('migrations')->where('migration', $this->relatedMigration)->delete();

        $this->artisan($this->command)
            ->assertFailed();
    }

    #[Test]
    public function test_it_does_not_run_if_encryption_is_off()
    {
        Settings::set('useEncryption', false);

        $this->artisan($this->command)
            ->assertFailed();
    }

    #[Test]
    public function test_it_tells_the_field_is_fully_encrypted_when_it_is()
    {
        TwoFAccount::factory()->for($this->user)->count(3)->create();

        Settings::set('useEncryption', true);

        $this->artisan($this->command)
            ->expectsOutputToContain('The Service field is fully encrypted.')
            ->assertSuccessful();
    }

    #[Test]
    public function test_it_encrypts_the_field_of_all_records()
    {
        TwoFAccount::factory()->for($this->user)->count(3)->create();
        $expectedServiceName = 'unencrypted_text';

        Settings::set('useEncryption', true);

        DB::table('twofaccounts')->update(['service' => $expectedServiceName]);
        $twofaccounts = TwoFAccount::all();
        foreach ($twofaccounts as $twofaccount) {
            $this->assertEquals(__('error.indecipherable'), $twofaccount->service);
        }

        $this->artisan($this->command)
            ->expectsConfirmation('Do you want to fix encryption of those records?', 'yes')
            ->assertSuccessful();

        foreach ($twofaccounts as $twofaccount) {
            $twofaccount->refresh();
            $this->assertEquals($expectedServiceName, $twofaccount->service);
        }
    }

    #[Test]
    public function test_it_does_not_encrypt_the_field_without_confirmation()
    {
        TwoFAccount::factory()->for($this->user)->count(3)->create();
        $expectedServiceName = 'unencrypted_text';

        Settings::set('useEncryption', true);

        DB::table('twofaccounts')->update(['service' => $expectedServiceName]);
        $twofaccounts = TwoFAccount::all();
        foreach ($twofaccounts as $twofaccount) {
            $this->assertEquals(__('error.indecipherable'), $twofaccount->service);
        }

        $this->artisan($this->command)
            ->expectsConfirmation('Do you want to fix encryption of those records?', 'no')
            ->assertSuccessful();

        foreach ($twofaccounts as $twofaccount) {
            $twofaccount->refresh();
            $this->assertEquals(__('error.indecipherable'), $twofaccount->service);
        }
    }

    #[Test]
    public function test_it_encrypts_the_field_of_invalid_records_only()
    {
        Settings::set('useEncryption', true);

        $expectedServiceName = 'myService';
        $twofaccounts        = TwoFAccount::factory()->for($this->user)->count(3)->create([
            'service' => $expectedServiceName,
        ]);

        $testedAccount = $twofaccounts[2];
        DB::table('twofaccounts')->where('id', $testedAccount->id)->update(['service' => $expectedServiceName]);

        $testedAccount->refresh();

        $this->assertEquals($expectedServiceName, $twofaccounts[0]->service);
        $this->assertEquals($expectedServiceName, $twofaccounts[1]->service);
        $this->assertEquals(__('error.indecipherable'), $testedAccount->service);

        $this->artisan($this->command)
            ->expectsConfirmation('Do you want to fix encryption of those records?', 'yes')
            ->assertSuccessful();

        $testedAccount->refresh();

        $this->assertEquals($expectedServiceName, $twofaccounts[0]->service);
        $this->assertEquals($expectedServiceName, $twofaccounts[1]->service);
        $this->assertEquals($expectedServiceName, $testedAccount->service);
    }

    #[Test]
    public function test_it_does_not_encrypt_the_record_if_encryption_is_not_consistent()
    {
        Settings::set('useEncryption', true);

        $expectedServiceName = 'myService';
        $twofaccounts        = TwoFAccount::factory()->for($this->user)->count(3)->create([
            'service' => $expectedServiceName,
        ]);

        $testedAccount = $twofaccounts[2];

        DB::table('twofaccounts')->where('id', $testedAccount->id)->update(['legacy_uri' => 'indecipherable_payload']);
        DB::table('twofaccounts')->where('id', $testedAccount->id)->update(['service' => $expectedServiceName]);

        $testedAccount->refresh();

        $this->assertEquals($expectedServiceName, $twofaccounts[0]->service);
        $this->assertEquals($expectedServiceName, $twofaccounts[1]->service);
        $this->assertEquals(__('error.indecipherable'), $testedAccount->service);

        $this->artisan($this->command)
            ->expectsConfirmation('Do you want to fix encryption of those records?', 'yes')
            ->expectsOutput('1 record could not be fixed, see log above for details.');

        $testedAccount->refresh();

        $this->assertEquals($expectedServiceName, $twofaccounts[0]->service);
        $this->assertEquals($expectedServiceName, $twofaccounts[1]->service);
        $this->assertEquals(__('error.indecipherable'), $testedAccount->service);
    }
}
