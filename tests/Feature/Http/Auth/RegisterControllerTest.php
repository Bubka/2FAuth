<?php

namespace Tests\Feature\Http\Auth;

use \App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;

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
        DB::table('users')->delete();

        $response = $this->json('POST', '/user', [
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
    public function test_register_returns_already_an_existing_user()
    {
        DB::table('users')->delete();
        $user = User::factory()->create();

        $response = $this->json('POST', '/user', [
                'name' => self::USERNAME,
                'email' => self::EMAIL,
                'password' => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
        ])
        ->assertJsonValidationErrorFor('name');
    }


    /**
     * @test
     */
    public function test_register_with_invalid_data_returns_validation_error()
    {
        $response = $this->json('POST', '/user', [
                'name' => null,
                'email' => self::EMAIL,
                'password' => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
            ])
            ->assertStatus(422);
    }

}