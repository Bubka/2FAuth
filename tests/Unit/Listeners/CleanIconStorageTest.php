<?php

namespace Tests\Unit\Listeners;

use App\Models\TwoFAccount;
use App\Events\TwoFAccountDeleted;
use Tests\TestCase;
use App\Listeners\CleanIconStorage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use App\Services\SettingService;


/**
 * @covers \App\Listeners\CleanIconStorage
 */
class CleanIconStorageTest extends TestCase
{
    public function test_it_deletes_icon_file_on_twofaccount_deletion()
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->andReturn(false);
        });

        $twofaccount = TwoFAccount::factory()->make();
        $event = new TwoFAccountDeleted($twofaccount);
        $listener = new CleanIconStorage();

        Storage::shouldReceive('disk->delete')
            ->with($event->twofaccount->icon)
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