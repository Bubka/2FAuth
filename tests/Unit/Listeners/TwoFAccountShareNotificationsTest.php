<?php

namespace Tests\Unit\Listeners;

use App\Events\TwoFAccountOwnershipTransferred;
use App\Events\TwoFAccountShareRevoked;
use App\Events\TwoFAccountShared;
use App\Listeners\SendTwoFAccountOwnershipTransferredNotification;
use App\Listeners\SendTwoFAccountShareRevokedNotification;
use App\Listeners\SendTwoFAccountSharedNotification;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(SendTwoFAccountOwnershipTransferredNotification::class)]
#[CoversClass(SendTwoFAccountSharedNotification::class)]
#[CoversClass(SendTwoFAccountShareRevokedNotification::class)]
class TwoFAccountShareNotificationsTest extends TestCase
{
    #[Test]
    public function test_listeners_are_registered_to_events() : void
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountOwnershipTransferred::class,
            SendTwoFAccountOwnershipTransferredNotification::class
        );

        Event::assertListening(
            TwoFAccountShared::class,
            SendTwoFAccountSharedNotification::class
        );

        Event::assertListening(
            TwoFAccountShareRevoked::class,
            SendTwoFAccountShareRevokedNotification::class
        );
    }

}
