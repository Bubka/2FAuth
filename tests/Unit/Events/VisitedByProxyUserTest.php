<?php

namespace Tests\Unit\Events;

use App\Events\VisitedByProxyUser;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * VisitedByProxyUserTest test class
 */
#[CoversClass(VisitedByProxyUser::class)]
class VisitedByProxyUserTest extends TestCase
{
    #[Test]
    public function test_event_constructor()
    {
        $user  = new User;
        $event = new VisitedByProxyUser($user);

        $this->assertSame($user, $event->user);
    }
}
