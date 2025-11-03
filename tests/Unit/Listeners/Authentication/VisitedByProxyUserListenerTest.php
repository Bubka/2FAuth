<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Events\VisitedByProxyUser;
use App\Listeners\Authentication\VisitedByProxyUserListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\UnexpectedEvent;
use Tests\TestCase;
use TypeError;

/**
 * VisitedByProxyUserListenerTest test class
 */
#[CoversClass(VisitedByProxyUserListener::class)]
class VisitedByProxyUserListenerTest extends TestCase
{
    #[Test]
    public function test_VisitedByProxyUserListener_listen_to_VisitedByProxyUser_event()
    {
        Event::fake();

        Event::assertListening(
            VisitedByProxyUser::class,
            VisitedByProxyUserListener::class
        );
    }

    #[Test]
    public function test_handle_throws_exception_with_unexpected_event_type()
    {
        $this->expectException(TypeError::class);

        $request  = Mockery::mock(Request::class);
        $event    = Mockery::mock(UnexpectedEvent::class);
        $listener = new VisitedByProxyUserListener($request);

        $listener->handle($event);
    }
}
