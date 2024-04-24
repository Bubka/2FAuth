<?php

namespace Tests\Unit\Listeners\Authentication;

use App\Events\VisitedByProxyUser;
use App\Listeners\Authentication\VisitedByProxyUserListener;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

/**
 * VisitedByProxyUserListenerTest test class
 */
#[CoversClass(VisitedByProxyUserListener::class)]
class VisitedByProxyUserListenerTest extends TestCase
{
    /**
     * @test
     */
    public function test_VisitedByProxyUserListener_listen_to_VisitedByProxyUser_event()
    {
        Event::fake();

        Event::assertListening(
            VisitedByProxyUser::class,
            VisitedByProxyUserListener::class
        );
    }
}
