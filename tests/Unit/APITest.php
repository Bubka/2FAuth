<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * test Account creation via API
     *
     * @return void
     */
    public function testAccountCreation()
    {
        $response = $this->json('POST', '/api/account', [
            'name' => 'Unit Test Account',
            'email' => str_random(10) . '@demo.com',
            'password' => '12345',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token', 'name']
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'message' => 'Category Created'
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
            'email' => 'demo@demo.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token']
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

        $category = \App\Account::create([
            'name' => 'To be deleted',
            'secret' => 'To be deleted'
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/account/{$category->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Account Deleted'
            ]);
    }

}
