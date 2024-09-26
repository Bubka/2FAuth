<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Listeners\Authentication\LogoutListener;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\UnexpectedEvent;
use Tests\TestCase;
use TypeError;

/**
 * LogoutListenerTest test class
 */
#[CoversClass(LogoutListener::class)]
class LogoutListenerTest extends TestCase
{
    #[Test]
    public function test_LogoutListener_listen_to_Logout_event()
    {
        Event::fake();

        Event::assertListening(
            Logout::class,
            LogoutListener::class
        );
    }

    #[Test]
    public function test_handle_throws_exception_with_unexpected_event_type()
    {
        $this->expectException(TypeError::class);

        $request  = Mockery::mock(Request::class);
        $event    = Mockery::mock(UnexpectedEvent::class);
        $listener = new LogoutListener($request);

        $listener->handle($event);
    }
}
