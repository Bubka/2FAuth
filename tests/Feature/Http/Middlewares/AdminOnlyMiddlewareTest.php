<?php

namespace Tests\Feature\Http\Middlewares;

use App\Http\Middleware\AdminOnly;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Tests\FeatureTestCase;

class AdminOnlyMiddlewareTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function test_users_are_rejected()
    {
        $this->expectException(AuthorizationException::class);

        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $this->actingAs($user);

        $request    = Request::create('/admin', 'GET');
        $middleware = new AdminOnly;

        $response = $middleware->handle($request, function () {
        });
    }

    /**
     * @test
     */
    public function test_admins_pass()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $admin = User::factory()->administrator()->create();

        $this->actingAs($admin);

        $request    = Request::create('/admin', 'GET');
        $middleware = new AdminOnly;

        $response = $middleware->handle($request, function () {
        });

        $this->assertNull($response);
    }
}
