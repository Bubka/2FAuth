<?php

namespace Tests\Unit\Listeners;

use App\Listeners\LogNotificationListener;
use App\Models\User;
use App\Notifications\TestEmailSettingNotification;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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

    #[Test]
    public function test_handle_logs_notification_sending()
    {
        $event = new NotificationSent((new User()), (new TestEmailSettingNotification()), 'channel');
        $listener = new LogNotificationListener();

        Log::shouldReceive('info')->once();

        $listener->handle($event);
    }
}
