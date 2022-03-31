<?php

namespace Tests\Api\v1\Controllers\Auth;

use App\Models\User;
use Tests\FeatureTestCase;

class UserControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
    */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }


    /**
     * @test
     */
    public function test_show_existing_user_when_authenticated_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user')
            ->assertOk()
            ->assertExactJson([
                'name'  => $this->user->name,
                'id'    => $this->user->id,
                'email' => $this->user->email,
            ]);
    }


    /**
     * @test
     */
    public function test_show_existing_user_when_anonymous_returns_success()
    {
        $response = $this->json('GET', '/api/v1/user/name')
            ->assertOk()
            ->assertExactJson([
                'name'  => $this->user->name,
            ]);
    }


    /**
     * @test
     */
    public function test_show_missing_user_returns_success_with_null_name()
    {
        User::destroy($this->user->id);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/user')
            ->assertOk()
            ->assertExactJson([
                'name'  => $this->user->name,
                'id'    => $this->user->id,
                'email' => $this->user->email,
            ]);
    }

}