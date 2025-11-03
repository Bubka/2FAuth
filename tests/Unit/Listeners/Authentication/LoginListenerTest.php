<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Listeners\Authentication\LoginListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\UnexpectedEvent;
use Tests\TestCase;
use TypeError;

/**
 * LoginListenerTest test class
 */
#[CoversClass(LoginListener::class)]
class LoginListenerTest extends TestCase
{
    #[Test]
    public function test_LoginListener_listen_to_Login_event()
    {
        Event::fake();

        Event::assertListening(
            Login::class,
            LoginListener::class
        );
    }

    #[Test]
    public function test_handle_throws_exception_with_unexpected_event_type()
    {
        $this->expectException(TypeError::class);

        $request  = Mockery::mock(Request::class);
        $event    = Mockery::mock(UnexpectedEvent::class);
        $listener = new LoginListener($request);

        $listener->handle($event);
    }
}
