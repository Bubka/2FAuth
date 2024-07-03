<?php

namespace Tests\Feature;

use App\Events\ScanForNewReleaseCalled;
use App\Http\Controllers\SinglePageController;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(SinglePageController::class)]
class ViewTest extends FeatureTestCase
{
    #[Test]
    public function test_landing_view_is_returned()
    {
        $response = $this->get('/');

        $response->assertSuccessful()
            ->assertViewIs('landing');
    }

    #[Test]
    public function test_landing_view_has_data()
    {
        $response = $this->get('/');

        $response->assertViewHas('appSettings');
        $response->assertViewHas('appConfig');
        $response->assertViewHas('defaultPreferences');
        $response->assertViewHas('subdirectory');
        $response->assertViewHas('isDemoApp');
        $response->assertViewHas('isTestingApp');
        $response->assertViewHas('lang');
        $response->assertViewHas('locales');
    }
    
    #[Test]
    public function test_calling_index_fires_ScanForNewReleaseCalled_event()
    {
        Event::fake();

        $this->get('/');

        Event::assertDispatched(ScanForNewReleaseCalled::class);
    }

    
}
