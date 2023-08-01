<?php

namespace Tests\Unit\Events;

use App\Events\TwoFAccountDeleted;
use App\Models\TwoFAccount;
use App\Services\SettingService;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

/**
 * TwoFAccountDeletedTest test class
 */
#[CoversClass(TwoFAccountDeleted::class)]
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

        $twofaccount = TwoFAccount::factory()->make();
        $event       = new TwoFAccountDeleted($twofaccount);

        $this->assertSame($twofaccount, $event->twofaccount);
    }
}
