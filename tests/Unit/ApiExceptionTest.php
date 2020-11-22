<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\TwoFAccount;
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
    public function testHttpUnauthenticated()
    {
        $response = $this->json('GET', '/api/settings/options')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }


    /**
     * test Not Found
     *
     * @test
     */
    public function testHttpNotFound()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1000')
            ->assertStatus(404);
    }


    /**
     * test Method Not Allowed
     *
     * @test
     */
    public function testHttpMethodNotAllowed()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/settings/options')
            ->assertStatus(405);
    }


    /**
     * test Unprocessable entity
     *
     * @test
     */
    public function testHttpUnprocessableEntity()
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
    public function testHttpInternalServerError()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/twofaccounts/reorder', [
                'orderedIds' => 'x'])
            ->assertStatus(500);
            
    }

}