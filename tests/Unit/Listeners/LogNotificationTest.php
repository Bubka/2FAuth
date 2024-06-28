<?php

namespace Tests\Unit\Listeners;

use App\Listeners\LogNotificationListener;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * LogNotificationTest test class
 */
#[CoversClass(LogNotificationListener::class)]
class LogNotificationTest extends TestCase
{
    #[Test]
    public function test_LogNotificationTest_listen_to_NotificationSent_event()
    {
        Event::fake();

        Event::assertListening(
            NotificationSent::class,
            LogNotificationListener::class
        );
    }
}
