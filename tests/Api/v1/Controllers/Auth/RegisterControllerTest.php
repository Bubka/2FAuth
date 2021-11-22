<?php

namespace Tests\Api\v1\Controllers\Auth;

use Tests\FeatureTestCase;

class RegisterControllerTest extends FeatureTestCase
{
    private const USERNAME = 'john doe';
    private const EMAIL = 'johndoe@example.org';
    private const PASSWORD =  'password';


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();
    }
    

    /**
     * @test
     */
    public function test_register_returns_success()
    {
        $response = $this->json('POST', '/api/v1/user', [
                'name' => self::USERNAME,
                'email' => self::EMAIL,
                'password' => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
        ])
        ->assertCreated()
        ->assertJsonStructure([
            'message',
            'name',
        ])
        ->assertJsonFragment([
            'name' => self::USERNAME,
        ]);
    }


    /**
     * @test
     */
    public function test_register_with_invalid_data_returns_validation_error()
    {
        $response = $this->json('POST', '/api/v1/user', [
                'name' => null,
                'email' => self::EMAIL,
                'password' => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
            ])
            ->assertStatus(422);
    }

}