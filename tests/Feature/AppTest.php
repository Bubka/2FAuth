<?php

namespace Tests\Feature;

use App\Providers\EventServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * AppTest class
 */
#[CoversClass(EventServiceProvider::class)]
class AppTest extends FeatureTestCase
{
    #[Test]
    public function test_events_should_be_explicitly_registered()
    {
        $eventServiceProvider = new EventServiceProvider(app());

        $this->assertFalse($eventServiceProvider->shouldDiscoverEvents());
    }
}
