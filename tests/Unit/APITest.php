<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Auth\Authenticatable;

class APITest extends TestCase
{

    /**
     * Rollback and execute migrations for each test.
     */
    use DatabaseTransactions;


    /**
     * set up fresh db
     */
    public function setUp(): void
    {
        parent::setUp();
        // Artisan::call('make:migrate', ['--force' => true]);
        Artisan::call('migrate', ['--seed' => true]);
        Artisan::call('passport:install',['--verbose' => 2]);
    }


    /**
     * test User creation via API
     *
     * @return void
     */
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'testCreate',
            'email' => str_random(10) . '@test.com',
            'password' => 'test',
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
            'email' => 'test@test.com',
            'password' => 'test'
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
            'name' => 'testCreation',
            'secret' => 'test',
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
            'name' => 'testDelete',
            'secret' => 'test'
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', "/api/account/{$account->id}")
            ->assertStatus(200)->assertJson([
                'status' => true,
                'message' => 'Account Deleted'
            ]);
    }

}
