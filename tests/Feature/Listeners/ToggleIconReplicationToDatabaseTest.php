<?php

namespace Tests\Feature\Listeners;

use App\Events\StoreIconsInDatabaseSettingChanged;
use App\Facades\IconStore;
use App\Facades\Settings;
use App\Listeners\ToggleIconReplicationToDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * ToggleIconReplicationToDatabaseTest test class
 */
#[CoversClass(ToggleIconReplicationToDatabase::class)]
class ToggleIconReplicationToDatabaseTest extends FeatureTestCase
{
    #[Test]
    public function test_it_triggers_database_replication()
    {
        IconStore::shouldReceive('setDatabaseReplication')->once();

        Settings::set('storeIconsInDatabase', true);
    }

    #[Test]
    public function test_it_listens_to_storeIconsInDatabaseSettingChanged_event()
    {
        Event::fake();

        Event::assertListening(
            StoreIconsInDatabaseSettingChanged::class,
            ToggleIconReplicationToDatabase::class
        );
    }
}
