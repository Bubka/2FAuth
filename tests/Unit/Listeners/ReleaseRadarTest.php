<?php

namespace Tests\Unit\Listeners;

use App\Events\ScanForNewReleaseCalled;
use App\Listeners\ReleaseRadar;
use App\Services\ReleaseRadarService;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ReleaseRadarTest test class
 */
#[CoversClass(ReleaseRadar::class)]
class ReleaseRadarTest extends TestCase
{
    #[Test]
    public function test_it_starts_release_scan()
    {
        $this->mock(ReleaseRadarService::class, function (MockInterface $releaseRadarService) {
            $releaseRadarService->shouldReceive('scheduledScan');
        });

        $event    = new ScanForNewReleaseCalled;
        $listener = new ReleaseRadar;

        $this->assertNull($listener->handle($event));
    }

    #[Test]
    public function test_ReleaseRadar_listen_to_ScanForNewReleaseCalled_event()
    {
        Event::fake();

        Event::assertListening(
            ScanForNewReleaseCalled::class,
            ReleaseRadar::class
        );
    }
}
