<?php

namespace Tests\Unit\Events;

use App\TwoFAccount;
use App\Events\TwoFAccountDeleted;
use Tests\TestCase;


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
        \Facades\App\Services\SettingService::shouldReceive('get')
            ->with('useEncryption')
            ->andReturn(false);

        $twofaccount = factory(TwoFAccount::class)->make();
        $event = new TwoFAccountDeleted($twofaccount);

        $this->assertSame($twofaccount, $event->twofaccount);
    }
}