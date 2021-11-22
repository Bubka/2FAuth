<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class RouteTest extends FeatureTestCase
{

    /**
     * test return main web view
     *
     * @test
     */
    public function test_landing_view_is_returned()
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
    public function test_exception_handler_with_web_route()
    {
        $response = $this->post('/');

        $response->assertStatus(405);
    }

}