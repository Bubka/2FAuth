<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class RouteTest extends FeatureTestCase
{
    #[Test]
    public function test_landing_view_is_returned()
    {
        $response = $this->get(route('landing', ['any' => '/']));

        $response->assertSuccessful()
            ->assertViewIs('landing');
    }

    #[Test]
    public function test_exception_handler_with_web_route()
    {
        $response = $this->post('/');

        $response->assertStatus(405);
    }
}
