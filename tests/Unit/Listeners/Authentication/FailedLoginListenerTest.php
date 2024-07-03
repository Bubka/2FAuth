<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Listeners\Authentication\FailedLoginListener;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\unexpectedEvent;
use Tests\TestCase;
use TypeError;

/**
 * FailedLoginListenerTest test class
 */
#[CoversClass(FailedLoginListener::class)]
class FailedLoginListenerTest extends TestCase
{
    #[Test]
    public function test_FailedLoginListener_listen_to_Failed_event()
    {
        Event::fake();

        Event::assertListening(
            Failed::class,
            FailedLoginListener::class
        );
    }

    #[Test]
    public function test_handle_throws_exception_with_unexpected_event_type()
    {
        $this->expectException(TypeError::class);
        
        $request  = Mockery::mock(Request::class);
        $event    = Mockery::mock(unexpectedEvent::class);
        $listener = new FailedLoginListener($request);

        $listener->handle($event);
    }

    #[Test]
    public function test_handle_send_nothing_if_user_is_null()
    {
        Notification::fake();
        
        $request  = Mockery::mock(Request::class);
        $event = Mockery::mock(Failed::class);

        (new FailedLoginListener($request))->handle($event);

        Notification::assertNothingSent();
    }

}
