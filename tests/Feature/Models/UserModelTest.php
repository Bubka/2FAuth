<?php

namespace Tests\Feature\Models;

use App\Models\AuthLog;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * UserModelTest test class
 */
#[CoversClass(User::class)]
class UserModelTest extends FeatureTestCase
{
    #[Test]
    public function test_admin_scope_returns_only_admin()
    {
        User::factory()->count(4)->create();

        $firstAdmin = User::factory()->administrator()->create([
            'name' => 'first',
        ]);
        $secondAdmin = User::factory()->administrator()->create([
            'name' => 'secondAdmin',
        ]);

        $admins = User::admins()->get();

        $this->assertCount(2, $admins);
        $this->assertEquals($admins[0]->is_admin, true);
        $this->assertEquals($admins[1]->is_admin, true);
        $this->assertEquals($admins[0]->name, $firstAdmin->name);
        $this->assertEquals($admins[1]->name, $secondAdmin->name);
    }

    #[Test]
    public function test_isAdministrator_returns_correct_state()
    {
        $user  = User::factory()->create();
        $admin = User::factory()->administrator()->create();

        $this->assertEquals($user->isAdministrator(), false);
        $this->assertEquals($admin->isAdministrator(), true);
    }

    #[Test]
    public function test_promoteToAdministrator_sets_administrator_status()
    {
        $user = User::factory()->create();

        $user->promoteToAdministrator();

        $this->assertEquals($user->isAdministrator(), true);
    }

    #[Test]
    public function test_promoteToAdministrator_demote_administrator_status()
    {
        $admin = User::factory()->administrator()->create();
        // We need another admin to prevent demoting event returning false
        // and blocking the demotion
        $another_admin = User::factory()->administrator()->create();

        $admin->promoteToAdministrator(false);
        $admin->save();

        $this->assertFalse($admin->isAdministrator());
    }

    #[Test]
    public function test_resetPassword_resets_password_with_success()
    {
        $user        = User::factory()->create();
        $oldPassword = $user->password;

        $user->resetPassword();

        $this->assertNotEquals($user->password, $oldPassword);
    }

    #[Test]
    public function test_resetPassword_dispatch_event()
    {
        Event::fake();
        $user = User::factory()->create();

        Event::assertDispatched(
            PasswordReset::class,
            $user->resetPassword()
        );
    }

    #[Test]
    public function test_delete_removes_user_data()
    {
        Artisan::call('passport:install', [
            '--verbose' => 2,
            '--no-interaction' => 1
        ]);
        
        $user = User::factory()->create();
        TwoFAccount::factory()->for($user)->create();
        AuthLog::factory()->for($user, 'authenticatable')->create();
        Group::factory()->for($user)->create();

        DB::table('webauthn_credentials')->insert([
            'id'                   => '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg',
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $user->id,
            'user_id'              => 'e8af6f703f8042aa91c30cf72289aa07',
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => 'eyJpdiI6Imp0U0NVeFNNbW45KzEvMXpad2p2SUE9PSIsInZhbHVlIjoic0VxZ2I1WnlHM2lJakhkWHVkK2kzMWtibk1IN2ZlaExGT01qOElXMDdRTjhnVlR0TDgwOHk1S0xQUy9BQ1JCWHRLNzRtenNsMml1dVQydWtERjFEU0h0bkJGT2RwUXE1M1JCcVpablE2Y2VGV2YvVEE2RGFIRUE5L0x1K0JIQXhLVE1aNVNmN3AxeHdjRUo2V0hwREZSRTJYaThNNnB1VnozMlVXZEVPajhBL3d3ODlkTVN3bW54RTEwSG0ybzRQZFFNNEFrVytUYThub2IvMFRtUlBZamoyZElWKzR1bStZQ1IwU3FXbkYvSm1FU2FlMTFXYUo0SG9kc1BDME9CNUNKeE9IelE5d2dmNFNJRXBKNUdlVzJ3VHUrQWJZRFluK0hib0xvVTdWQ0ZISjZmOWF3by83aVJES1dxbU9Zd1lhRTlLVmhZSUdlWmlBOUFtcTM2ZVBaRWNKNEFSQUhENk5EaC9hN3REdnVFbm16WkRxekRWOXd4cVcvZFdKa2tlWWJqZWlmZnZLS0F1VEVCZEZQcXJkTExiNWRyQmxsZWtaSDRlT3VVS0ZBSXFBRG1JMjRUMnBKRXZxOUFUa2xxMjg2TEplUzdscVo2UytoVU5SdXk1OE1lcFN6aU05ZkVXTkdIM2tKM3Q5bmx1TGtYb1F5bGxxQVR3K3BVUVlia1VybDFKRm9lZDViNzYraGJRdmtUb2FNTEVGZmZYZ3lYRDRiOUVjRnJpcTVvWVExOHJHSTJpMnVBZ3E0TmljbUlKUUtXY2lSWDh1dE5MVDNRUzVRSkQrTjVJUU8rSGhpeFhRRjJvSEdQYjBoVT0iLCJtYWMiOiI5MTdmNWRkZGE5OTEwNzQ3MjhkYWVhYjRlNjk0MWZlMmI5OTQ4YzlmZWI1M2I4OGVkMjE1MjMxNjUwOWRmZTU2IiwidGFnIjoiIn0=',
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $user->createToken('myToken', []);
        Password::broker('webauthn')->createToken($user);
        Password::broker()->createToken($user);

        $user->delete();

        $this->assertDatabaseMissing('twofaccounts', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseMissing('groups', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseMissing('webauthn_credentials', [
            'authenticatable_id' => $user->id,
        ]);
        $this->assertDatabaseMissing(config('auth.passwords.webauthn.table'), [
            'email' => $user->email,
        ]);
        $this->assertDatabaseMissing('oauth_access_tokens', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseMissing(config('auth.passwords.users.table'), [
            'email' => $user->email,
        ]);
        $this->assertDatabaseMissing('auth_logs', [
            'authenticatable_id' => $user->id,
        ]);
    }

    #[Test]
    public function test_delete_flushes_icons_of_user_twofaccounts()
    {
        Storage::fake('icons');

        $user = User::factory()->create();

        $twofaccount = TwoFAccount::factory()->for($user)->create();
        $twofaccount->setIcon(base64_decode(OtpTestData::ICON_PNG_DATA), 'png');
        $twofaccount->save();
        Storage::disk('icons')->assertExists($twofaccount->icon);

        $user->delete();

        Storage::disk('icons')->assertMissing($twofaccount->icon);
    }

    #[Test]
    public function test_delete_does_not_delete_the_only_admin()
    {
        $admin = User::factory()->administrator()->create();
        $this->assertEquals(1, User::admins()->count());

        $isDeleted = $admin->delete();

        $this->assertFalse($isDeleted);
    }
}
