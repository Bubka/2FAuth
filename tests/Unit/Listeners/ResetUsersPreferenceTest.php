<?php

namespace Tests\Unit\Listeners;

use App\Events\GroupDeleted;
use App\Listeners\ResetUsersPreference;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ResetUsersPreferenceTest test class
 */
#[CoversClass(ResetUsersPreference::class)]
class ResetUsersPreferenceTest extends TestCase
{
    #[Test]
    public function test_ResetUsersPreference_listen_to_GroupDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            GroupDeleted::class,
            ResetUsersPreference::class
        );
    }
}
