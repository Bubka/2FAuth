<?php

namespace Tests\Unit\Events;

use App\Events\storeIconsInDatabaseSettingChanged;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * storeIconsInDatabaseSettingChangedTest test class
 */
#[CoversClass(storeIconsInDatabaseSettingChanged::class)]
class storeIconsInDatabaseSettingChangedTest extends TestCase
{
    #[Test]
    public function test_event_constructor()
    {
        $newValue = true;
        $event = new storeIconsInDatabaseSettingChanged($newValue);

        $this->assertSame($newValue, $event->newValue);
    }
}
