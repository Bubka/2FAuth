<?php

namespace Tests\Unit\Events;

use App\Models\TwoFAccount;
use App\Events\TwoFAccountDeleted;
use Tests\TestCase;
use Mockery\MockInterface;
use App\Services\SettingService;

/**
 * @covers \App\Events\TwoFAccountDeleted
 */
class TwoFAccountDeletedTest extends TestCase
{
    /**
     * @test
     */
    public function test_event_constructor()
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->andReturn(false);
        });

        // SettingService::shouldReceive('get')
        //             ->andReturn(false);

        // $settingService->shouldReceive('get')
        //     ->with('useEncryption')
        //     ->andReturn(false);

        // $settingService->shouldReceive('get')
        //     ->with('getOfficialIcons')
        //     ->andReturn(false);

        // \Facades\App\Services\SettingService::shouldReceive('get')
        //     ->with('useEncryption')
        //     ->andReturn(false);

        $twofaccount = TwoFAccount::factory()->make();
        $event = new TwoFAccountDeleted($twofaccount);

        $this->assertSame($twofaccount, $event->twofaccount);
    }
}