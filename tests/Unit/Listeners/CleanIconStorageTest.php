<?php

namespace Tests\Unit\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Listeners\CleanIconStorage;
use App\Models\TwoFAccount;
use App\Services\IconStoreService;
use App\Services\SettingService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * CleanIconStorageTest test class
 */
#[CoversClass(CleanIconStorage::class)]
class CleanIconStorageTest extends TestCase
{
    #[Test]
    public function test_it_deletes_icon_file_using_the_iconstore()
    {
        $this->mock(SettingService::class, function (MockInterface $iconStore) {
            foreach (config('2fauth.settings') as $setting => $value) {
                $iconStore->shouldReceive('get')
                    ->with($setting)
                    ->andReturn($value);
            }
        });

        $twofaccount = TwoFAccount::factory()->make();
        $event       = new TwoFAccountDeleted($twofaccount);
        $listener    = App::make(CleanIconStorage::class);

        $mockedIconStore = $this->mock(IconStoreService::class, function (MockInterface $iconStore) use ($event) {
            $iconStore->shouldReceive('delete')
                ->once()
                ->with($event->twofaccount->icon)
                ->andReturn(true);
        });

        /**
         * @disregard P1009 Undefined type
         */
        $this->assertNull($listener->handle($event, $mockedIconStore));
    }

    #[Test]
    public function test_CleanIconStorage_listen_to_TwoFAccountDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountDeleted::class,
            CleanIconStorage::class
        );
    }
}
