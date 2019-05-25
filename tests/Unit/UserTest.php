<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Auth\Authenticatable;

class UserTest extends TestCase
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

}
