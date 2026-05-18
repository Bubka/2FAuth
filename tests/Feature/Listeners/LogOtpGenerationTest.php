<?php

namespace Tests\Feature\Listeners;

use App\Events\OtpGenerated;
use App\Listeners\LogOtpGeneration;
use App\Models\OtpLog;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * LogOtpGenerationTest test class
 */
#[CoversClass(LogOtpGeneration::class)]
class LogOtpGenerationTest extends FeatureTestCase
{
    #[Test]
    public function test_it_listens_to_otpGenerated_event()
    {
        Event::fake();

        Event::assertListening(
            OtpGenerated::class,
            LogOtpGeneration::class
        );
    }

    #[Test]
    public function test_handle_adds_totp_log_entry()
    {
        $user = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($user)->create();
        $ip = '127.0.0.1';

        $this->actingAs($user);
        $request = Mockery::mock(Request::class);

        $request->shouldReceive('ip')
            ->andReturn($ip);

        $totpDto = $twofaccount->getOTP();
        
        $otpLogs = OtpLog::all();
        $this->assertCount(1, $otpLogs);

        $otpLog = OtpLog::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('requester_id', $user->id)
            ->where('requester_name', $user->name)
            ->where('requester_email', $user->email)
            ->where('owner_id', $user->id)
            ->where('owner_name', $user->name)
            ->where('owner_email', $user->email)
            ->where('ip_address', $ip)
            ->where('otp_type', $totpDto->otp_type)
            ->where('generated_at', $totpDto->generated_at)
            ->get();

        $this->assertNotNull($otpLog);
    }

    #[Test]
    public function test_handle_adds_hotp_log_entry()
    {
        $user = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($user)->create(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP);
        $ip = '127.0.0.1';

        $this->actingAs($user);
        $request = Mockery::mock(Request::class);

        $request->shouldReceive('ip')
            ->andReturn($ip);

        $totpDto = $twofaccount->getOTP();
        
        $otpLogs = OtpLog::all();
        $this->assertCount(1, $otpLogs);

        $otpLog = OtpLog::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('requester_id', $user->id)
            ->where('requester_name', $user->name)
            ->where('requester_email', $user->email)
            ->where('owner_id', $user->id)
            ->where('owner_name', $user->name)
            ->where('owner_email', $user->email)
            ->where('ip_address', $ip)
            ->where('otp_type', $totpDto->otp_type)
            ->where('counter', $totpDto->counter)
            ->get();

        $this->assertNotNull($otpLog);
    }

    #[Test]
    public function test_handle_adds_otp_log_entry_for_shared_account()
    {
        $user = User::factory()->create();
        $requester = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($user)->create();
        $ip = '127.0.0.1';

        TwoFAccountShare::create([
            'twofaccount_id' => $twofaccount->id,
            'shared_with_user_id' => $requester->id,
            'scope' => TwoFAccountShare::SCOPE_USER,
            'created_by_user_id' => $user->id,
        ]);

        $this->actingAs($requester);
        $request = Mockery::mock(Request::class);

        $request->shouldReceive('ip')
            ->andReturn($ip);

        $totpDto = $twofaccount->getOTP();
        
        $otpLogs = OtpLog::all();
        $this->assertCount(1, $otpLogs);

        $otpLog = OtpLog::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('requester_id', $requester->id)
            ->where('requester_name', $requester->name)
            ->where('requester_email', $requester->email)
            ->where('owner_id', $user->id)
            ->where('owner_name', $user->name)
            ->where('owner_email', $user->email)
            ->where('ip_address', $ip)
            ->where('otp_type', $totpDto->otp_type)
            ->where('generated_at', $totpDto->generated_at)
            ->get();

        $this->assertNotNull($otpLog);
    }
}
