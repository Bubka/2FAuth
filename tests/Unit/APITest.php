<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Authenticatable;

class APITest extends TestCase
{
    /**
     * test User creation via API
     *
     * @return void
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Demo User',
            'email' => str_random(10) . '@phpunit.com',
            'password' => '12345',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token', 'name']
        ]);
    }


    /**
     * test User login via API
     *
     * @return void
     */
    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'edouard@ganeau.me',
            'password' => 'bubka'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token']
        ]);
    }

    /**
     * test Account creation via API
     *
     * @return void
     */
    public function testAccountCreation()
    {
        //$this->withoutMiddleware();

        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/account', [
            'name' => 'phpunit account #' . str_random(5),
            'secret' => '3GB2I2P365J575LS',
        ]);

        $response->assertStatus(200)->assertJson([
            // 'data' => $response->data,
            'status' => true,
            'message' => 'Account Created'
        ]);
    }


    /**
     * test Account index fetching via API
     *
     * @return void
     */
    public function testAccountFetch()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/account')
            ->assertStatus(200)->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'secret',
                    'icon',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]
        );
    }


    /**
     * test Account deletion via API
     * @return [type] [description]
     */
    public function testAccountDeletion()
    {
        $user = \App\User::find(1);

        $account = \App\Account::create([
            'name' => 'To be deleted #' . str_random(5),
            'secret' => '12345'
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/account/{$account->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Account Deleted'
            ]);
    }

}
