<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;


#[CoversMethod(RouteServiceProvider::class, 'boot')]
class RouteTest extends FeatureTestCase
{
    const API_ROUTE_PREFIX = 'api/v1';
    const API_MIDDLEWARE = 'api.v1';

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

    #[Test]
    public function test_all_api_routes_are_behind_apiv1_middleware()
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $middlewares = Route::gatherRouteMiddleware($route);

            if (Str::startsWith($route->uri(), self::API_ROUTE_PREFIX)) {
                $this->assertEquals(self::API_ROUTE_PREFIX, $route->getPrefix());
                $this->assertTrue(in_array(self::API_MIDDLEWARE, $middlewares));
            }
        }
    }

    #[Test]
    #[DataProvider('wherePatternProvider')]
    public function test_router_has_expected_global_where_patterns($pattern)
    {
        $patterns = Route::getPatterns();

        $this->assertArrayHasKey($pattern, $patterns);
    }

    /**
     * Provide data for tests
     */
    public static function wherePatternProvider()
    {
        return [
            'SETTING_NAME' => ['settingName']
        ];
    }

    
}
