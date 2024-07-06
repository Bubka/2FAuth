<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(RouteServiceProvider::class)]
class RouteTest extends FeatureTestCase
{
    const API_ROUTE_PREFIX = 'api/v1';
    const API_MIDDLEWARE = 'api.v1';

    #[Test]
    public function test_exception_handler_with_web_route()
    {
        $response = $this->post('/');

        $response->assertStatus(405);
    }

    #[Test]
    public function test_all_api_routes_are_behind_apiv1_middleware()
    {
        Artisan::call('route:clear');
        Artisan::call('route:cache');
        $this->get('/');

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $middlewares = [];
            
            try {
                $uri = $route->uri;
                $middlewares = Route::gatherRouteMiddleware($route);
            }
            catch (\Exception $ex)
            {
                $uri = $route->uri;
                //return;
            }

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
