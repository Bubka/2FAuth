<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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
        Artisan::call('route:cache');
        $this->get('/');

        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if (Str::startsWith($route->uri(), self::API_ROUTE_PREFIX)) {
                // Route middlewares can be set via action or controllers.
                // Using $route->middleware() fetches middlewares from action only.
                // Route::gatherRouteMiddleware($route) would have fetch middlewares from
                // both action & controllers but the "Route is not bound" exception is thrown then.
                // $route->middleware() is acceptable as no middleware is set from controllers in 2FAuth.
                $this->assertEquals(self::API_ROUTE_PREFIX, $route->getPrefix());
                $this->assertTrue(in_array(self::API_MIDDLEWARE, $route->middleware()));
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
            'SETTING_NAME' => ['settingName'],
        ];
    }
}
