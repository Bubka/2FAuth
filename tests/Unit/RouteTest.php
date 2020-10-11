<?php

namespace Tests\Unit;

use Tests\TestCase;

class RouteTest extends TestCase
{

    /**
     * test return main web view
     *
     * @test
     */
    public function testLandingViewIsReturned()
    {
        $response = $this->get(route('landing', ['any' => '/']));

        $response->assertSuccessful()
            ->assertViewIs('landing');
    }

    /**
     * test return main web view
     *
     * @test
     */
    public function testExceptionHandlerWithWebRoute()
    {
        $response = $this->post('/');

        $response->assertStatus(405);
    }

}