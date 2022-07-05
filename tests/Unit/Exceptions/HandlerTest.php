<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\Container;
use Tests\TestCase;


/**
 * @covers \App\Exceptions\Handler
 */
class HandlerTest extends TestCase
{

    /**
    * @test
    *
    * @dataProvider provideExceptionsforBadRequest
    */
    public function test_exceptions_returns_badRequest_json_response($exception)
    {
        $request = $this->createMock(Request::class);
        $instance = new Handler($this->createMock(Container::class));
        $class = new \ReflectionClass(Handler::class);

        $method = $class->getMethod('render');
        $method->setAccessible(true);

        $response = $method->invokeArgs($instance, [$request, $this->createMock($exception)]);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $response = \Illuminate\Testing\TestResponse::fromBaseResponse($response);
        $response->assertStatus(400)
            ->assertJsonStructure([
                'message'
            ]);
    }

    /**
     * Provide Valid data for validation test
     */
    public function provideExceptionsforBadRequest() : array
    {
        return [
            [
                '\App\Exceptions\InvalidOtpParameterException'
            ],
            [
                '\App\Exceptions\InvalidQrCodeException'
            ],
            [
                '\App\Exceptions\InvalidSecretException'
            ],
            [
                '\App\Exceptions\DbEncryptionException'
            ],
            [
                '\App\Exceptions\InvalidGoogleAuthMigration'
            ],
            [
                '\App\Exceptions\UndecipherableException'
            ],
            [
                '\App\Exceptions\UnsupportedOtpTypeException'
            ],
        ];
    }

    /**
    * @test
    *
    * @dataProvider provideExceptionsforNotFound
    */
    public function test_exceptions_returns_notFound_json_response($exception)
    {
        $request = $this->createMock(Request::class);
        $instance = new Handler($this->createMock(Container::class));
        $class = new \ReflectionClass(Handler::class);

        $method = $class->getMethod('render');
        $method->setAccessible(true);

        $response = $method->invokeArgs($instance, [$request, $this->createMock($exception)]);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $response = \Illuminate\Testing\TestResponse::fromBaseResponse($response);
        $response->assertStatus(404)
            ->assertJsonStructure([
                'message'
            ]);
    }

    /**
     * Provide Valid data for validation test
     */
    public function provideExceptionsforNotFound() : array
    {
        return [
            [
                '\Illuminate\Database\Eloquent\ModelNotFoundException'
            ],
            [
                '\Symfony\Component\HttpKernel\Exception\NotFoundHttpException'
            ],
        ];
    }

    /**
    * @test
    */
    public function test_authenticationException_returns_proxyAuthRequired_json_response_with_proxy_guard()
    {
        $request = $this->createMock(Request::class);
        $instance = new Handler($this->createMock(Container::class));
        $class = new \ReflectionClass(Handler::class);

        $method = $class->getMethod('render');
        $method->setAccessible(true);

        $mockException = $this->createMock(\Illuminate\Auth\AuthenticationException::class);
        $mockException->method("guards")->willReturn(['reverse-proxy-guard']);

        $response = $method->invokeArgs($instance, [$request, $mockException]);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $response = \Illuminate\Testing\TestResponse::fromBaseResponse($response);
        $response->assertStatus(407)
            ->assertJsonStructure([
                'message'
            ]);
    }
}