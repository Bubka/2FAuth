<?php

namespace Tests\Feature\Models;

use App\Models\AuthLog;
use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Observers\UserObserver;
use Database\Factories\AuthLogFactory;
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
#[CoversClass(UserObserver::class)]
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

    #[Test]
    public function test_getFromCredentialId_retreives_the_user()
    {
        $user = User::factory()->create();

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

        $searched = User::getFromCredentialId('-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg');

        $this->assertEquals($user->id, $searched->id);
    }

    #[Test]
    public function test_authentications_returns_user_auth_logs_sorted_by_latest_id()
    {
        $user = User::factory()->create();

        $tenDaysAgoAuthLog  = AuthLog::factory()->daysAgo(10)->for($user, 'authenticatable')->create();
        $fiveDaysAgoAuthLog = AuthLog::factory()->daysAgo(5)->for($user, 'authenticatable')->create();
        $lastAuthLog        = AuthLog::factory()->daysAgo(1)->for($user, 'authenticatable')->create();

        $authentications = $user->authentications()->get();

        $this->assertCount(3, $authentications);
        $this->assertEquals($lastAuthLog->id, $authentications[0]->id);
        $this->assertEquals($fiveDaysAgoAuthLog->id, $authentications[1]->id);
        $this->assertEquals($tenDaysAgoAuthLog->id, $authentications[2]->id);
    }

    #[Test]
    public function test_authentications_returns_user_auth_logs_only()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $userAuthLog  = AuthLog::factory()->daysAgo(10)->for($user, 'authenticatable')->create();
        AuthLog::factory()->daysAgo(5)->for($anotherUser, 'authenticatable')->create();

        $authentications = $user->authentications()->get();

        $this->assertCount(1, $authentications);
        $this->assertEquals($userAuthLog->id, $authentications[0]->id);
    }

    #[Test]
    public function test_authenticationsByPeriod_returns_last_month_auth_logs()
    {
        $user = User::factory()->create();

        $twoMonthsAgoAuthLog    = AuthLog::factory()->duringLastThreeMonth()->for($user, 'authenticatable')->create();
        $duringLastMonthAuthLog = AuthLog::factory()->duringLastMonth()->for($user, 'authenticatable')->create();

        $authentications = $user->authenticationsByPeriod(1);

        $this->assertCount(1, $authentications);
        $this->assertEquals($duringLastMonthAuthLog->id, $authentications[0]->id);
    }

    #[Test]
    public function test_authenticationsByPeriod_returns_last_three_months_auth_logs()
    {
        $user = User::factory()->create();

        $sixMonthsAgoAuthLog    = AuthLog::factory()->duringLastSixMonth()->for($user, 'authenticatable')->create();
        $threeMonthsAgoAuthLog  = AuthLog::factory()->duringLastThreeMonth()->for($user, 'authenticatable')->create();
        $duringLastMonthAuthLog = AuthLog::factory()->duringLastMonth()->for($user, 'authenticatable')->create();
        
        $authentications = $user->authenticationsByPeriod(3);

        $this->assertCount(2, $authentications);
        $this->assertEquals($duringLastMonthAuthLog->id, $authentications[0]->id);
        $this->assertEquals($threeMonthsAgoAuthLog->id, $authentications[1]->id);
    }

    #[Test]
    public function test_latestAuthentication_returns_user_latest_auth_logs()
    {
        $user = User::factory()->create();

        $twoMonthsAgoAuthLog    = AuthLog::factory()->duringLastThreeMonth()->for($user, 'authenticatable')->create();
        $duringLastMonthAuthLog = AuthLog::factory()->duringLastMonth()->for($user, 'authenticatable')->create();

        $authentications = $user->latestAuthentication()->get();

        $this->assertCount(1, $authentications);
        $this->assertEquals($duringLastMonthAuthLog->id, $authentications[0]->id);
    }

    #[Test]
    public function test_latestAuthentication_returns_user_latest_auth_logs_only()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $userAuthLog        = AuthLog::factory()->duringLastThreeMonth()->for($user, 'authenticatable')->create();
        $anotherUserAuthLog = AuthLog::factory()->duringLastMonth()->for($anotherUser, 'authenticatable')->create();

        $authentications = $user->latestAuthentication()->get();

        $this->assertCount(1, $authentications);
        $this->assertEquals($userAuthLog->id, $authentications[0]->id);
    }

    #[Test]
    public function test_lastLoginAt_returns_user_last_auth_date()
    {
        $user = User::factory()->create();
        $now = now();

        $tenDaysAgoAuthLog  = AuthLog::factory()->daysAgo(10)->for($user, 'authenticatable')->create();
        $fiveDaysAgoAuthLog = AuthLog::factory()->daysAgo(5)->for($user, 'authenticatable')->create();
        $lastAuthLog        = AuthLog::factory()->at($now)->for($user, 'authenticatable')->create();

        $lastLoginAt = $user->lastLoginAt();

        $this->assertEquals($lastLoginAt->startOfSecond(), $now->startOfSecond());
    }

    #[Test]
    public function test_lastLoginAt_returns_null_if_user_has_no_login()
    {
        $user = User::factory()->create();
        AuthLog::factory()->logoutOnly()->for($user, 'authenticatable')->create();

        $lastLoginAt = $user->lastLoginAt();

        $this->assertNull($lastLoginAt);
    }

    #[Test]
    public function test_lastSuccessfulLoginAt_returns_user_last_successful_login_date()
    {
        $user = User::factory()->create();
        $now = now();
        AuthLog::factory()->at($now)->for($user, 'authenticatable')->create();

        $lastSuccessfulLoginAt = $user->lastSuccessfulLoginAt();

        $this->assertEquals($lastSuccessfulLoginAt->startOfSecond(), $now->startOfSecond());
    }

    #[Test]
    public function test_lastSuccessfulLoginAt_returns_null_if_user_has_no_successful_login()
    {
        $user = User::factory()->create();
        $now = now();
        AuthLog::factory()->at($now)->failedLogin()->for($user, 'authenticatable')->create();

        $lastSuccessfulLoginAt = $user->lastSuccessfulLoginAt();

        $this->assertNull($lastSuccessfulLoginAt);
    }

    #[Test]
    public function test_lastLoginIp_returns_user_last_login_ip()
    {
        $user = User::factory()->create();
        AuthLog::factory()->for($user, 'authenticatable')->create();

        $lastLoginIp = $user->lastLoginIp();

        $this->assertEquals(AuthLogFactory::IP, $lastLoginIp);
    }

    #[Test]
    public function test_lastLoginIp_returns_null_if_user_has_no_auth_log()
    {
        $user = User::factory()->create();

        $lastLoginIp = $user->lastLoginIp();

        $this->assertNull($lastLoginIp);
    }

    #[Test]
    public function test_lastSuccessfulLoginIp_returns_user_last_successful_login_ip()
    {
        $user = User::factory()->create();
        AuthLog::factory()->for($user, 'authenticatable')->create();

        $lastSuccessfulLoginIp = $user->lastSuccessfulLoginIp();

        $this->assertEquals(AuthLogFactory::IP, $lastSuccessfulLoginIp);
    }

    #[Test]
    public function test_lastSuccessfulLoginIp_returns_null_if_user_has_no_successful_login()
    {
        $user = User::factory()->create();
        AuthLog::factory()->failedLogin()->for($user, 'authenticatable')->create();

        $lastSuccessfulLoginIp = $user->lastSuccessfulLoginIp();

        $this->assertNull($lastSuccessfulLoginIp);
    }

    #[Test]
    public function test_previousLoginAt_returns_user_last_auth_date()
    {
        $user = User::factory()->create();
        $now = now();
        $yesterday = now()->subDay();

        $yesterdayAuthLog   = AuthLog::factory()->at($yesterday)->for($user, 'authenticatable')->create();
        $lastAuthLog        = AuthLog::factory()->at($now)->for($user, 'authenticatable')->create();

        $previousLoginAt = $user->previousLoginAt();

        $this->assertEquals($previousLoginAt->startOfSecond(), $yesterday->startOfSecond());
    }

    #[Test]
    public function test_previousLoginAt_returns_null_if_user_has_no_auth_log()
    {
        $user = User::factory()->create();

        $previousLoginAt = $user->previousLoginAt();

        $this->assertNull($previousLoginAt);
    }

    #[Test]
    public function test_previousLoginIp_returns_user_last_auth_ip()
    {
        $user = User::factory()->create();
        $yesterday = now()->subDay();

        AuthLog::factory()->for($user, 'authenticatable')->create();
        AuthLog::factory()->at($yesterday)->for($user, 'authenticatable')->create();

        $previousLoginIp = $user->previousLoginIp();

        $this->assertEquals(AuthLogFactory::IP, $previousLoginIp);
    }

    #[Test]
    public function test_previousLoginIp_returns_null_if_user_has_no_auth_log()
    {
        $user = User::factory()->create();

        $previousLoginIp = $user->previousLoginIp();

        $this->assertNull($previousLoginIp);
    }

    
}
