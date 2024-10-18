<?php

namespace Tests\Unit\Listeners;

use App\Events\storeIconsInDatabaseSettingChanged;
use App\Listeners\ToggleIconReplicationToDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ToggleIconReplicationToDatabaseTest test class
 */
#[CoversClass(ToggleIconReplicationToDatabase::class)]
class ToggleIconReplicationToDatabaseTest extends TestCase
{
    #[Test]
    public function test_ToggleIconReplicationToDatabase_listen_to_storeIconsInDatabaseSettingChanged_event()
    {
        Event::fake();

        Event::assertListening(
            storeIconsInDatabaseSettingChanged::class,
            ToggleIconReplicationToDatabase::class
        );
    }
}
