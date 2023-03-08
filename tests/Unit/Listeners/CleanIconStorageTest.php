<?php

namespace Tests\Unit\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Listeners\CleanIconStorage;
use App\Models\TwoFAccount;
use App\Services\SettingService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @covers \App\Listeners\CleanIconStorage
 */
class CleanIconStorageTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_deletes_icon_file_using_storage_facade()
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->andReturn(false);
        });

        $twofaccount = TwoFAccount::factory()->make();
        $event       = new TwoFAccountDeleted($twofaccount);
        $listener    = new CleanIconStorage();

        Storage::shouldReceive('disk->delete')
            ->with($event->twofaccount->icon)
            ->andReturn(true);

        $this->assertNull($listener->handle($event));
    }

    /**
     * @test
     */
    public function test_CleanIconStorage_listen_to_TwoFAccountDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountDeleted::class,
            CleanIconStorage::class
        );
    }
}
