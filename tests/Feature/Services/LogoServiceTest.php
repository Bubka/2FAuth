<?php

namespace Tests\Feature\Services;

use App\Services\LogoService;
use Tests\FeatureTestCase;
use Tests\TestCase;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * @covers \App\Services\LogoService
 */
class LogoServiceTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();
    }


    /**
     * @test
     */
    public function test_getIcon_returns_iconFilename_when_logo_exists()
    {
        $logoServiceMock = $this->partialMock(LogoService::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getLogo', 'copyToIcons')
            ->once()
            ->andReturn('service.svg', true);
        });

        $icon = $logoServiceMock->getIcon('service');
        
        $this->assertNotNull($icon);
    }


    /**
     * @test
     */
    public function test_getIcon_returns_null_when_no_logo_exists()
    {
        $logoServiceMock = $this->partialMock(LogoService::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getLogo')
            ->once()
            ->andReturn(null);
        });

        $icon = $logoServiceMock->getIcon('no_logo_should_exists_with_this_name');
        
        $this->assertEquals(null, $icon);
    }

}