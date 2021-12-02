<?php

namespace Tests\Unit\Listeners;

use App\Models\TwoFAccount;
use App\Events\TwoFAccountDeleted;
use Tests\TestCase;
use App\Listeners\CleanIconStorage;
use Illuminate\Support\Facades\Storage;


/**
 * @covers \App\Listeners\CleanIconStorage
 */
class CleanIconStorageTest extends TestCase
{
    public function test_it_stores_time_to_session()
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
}