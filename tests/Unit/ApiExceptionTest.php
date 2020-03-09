<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Http\Controllers\TwoFAccountController;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\RequestGuard;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiExceptionTest extends TestCase
{
    /** @var \App\User */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * test Unauthorized
     *
     * @test
     */
    public function test_HTTP_UNAUTHORIZED()
    {
        $response = $this->json('GET', '/api/settings/options')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthorized'
            ]);
    }


    /**
     * test Unauthorized
     *
     * @test
     */
    public function test_HTTP_FORBIDDEN()
    {

    }


    /**
     * test Not Found
     *
     * @test
     */
    public function test_HTTP_NOT_FOUND()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1000')
            ->assertStatus(404)
            ->assertJson([
                'message' => 'Not Found'
            ]);
    }


    /**
     * test Method Not Allowed
     *
     * @test
     */
    public function test_HTTP_METHOD_NOT_ALLOWED()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/settings/options')
            ->assertStatus(405)
            ->assertJson([
                'message' => 'Method Not Allowed'
            ]);
    }


    /**
     * test Unprocessable entity
     *
     * @test
     */
    public function test_HTTP_UNPROCESSABLE_ENTITY()
    {
        $response = $this->json('POST', '/api/login')
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ])
            ->assertJsonValidationErrors([
                'email',
                'password'
            ]);
    }


    /**
     * test Internal Server error
     *
     * @test
     */
    public function test_HTTP_INTERNAL_SERVER_ERROR()
    {

    }

}