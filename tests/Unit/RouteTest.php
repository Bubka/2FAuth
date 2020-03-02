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
        $response = $this->get('/');

        $response->assertViewIs('landing');
    }

}