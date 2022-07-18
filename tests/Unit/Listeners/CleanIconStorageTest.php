<?php

namespace Tests\Unit\Listeners;

use App\Models\TwoFAccount;
use App\Events\TwoFAccountDeleted;
use Tests\TestCase;
use App\Listeners\CleanIconStorage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;


/**
 * @covers \App\Listeners\CleanIconStorage
 */
class CleanIconStorageTest extends TestCase
{
    public function test_it_deletes_icon_file_on_twofaccount_deletion()
    {
        \Facades\App\Services\SettingService::shouldReceive('get')
            ->with('useEncryption')
            ->andReturn(false);

        $twofaccount = TwoFAccount::factory()->make();
        $event = new TwoFAccountDeleted($twofaccount);
        $listener = new CleanIconStorage();

        Storage::shouldReceive('delete')
            ->with('public/icons/' . $event->twofaccount->icon)
            ->andReturn(true);

        $this->assertNull($listener->handle($event));
    }


    public function test_CleanIconStorage_listen_to_TwoFAccountDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountDeleted::class,
            CleanIconStorage::class
        );
    }
}