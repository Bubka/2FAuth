<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class WebAuthnRecoveryControllerTest extends FeatureTestCase
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
    public function test_options_returns_success()
    {
        $token = '$2y$10$hgGTVVTRLsSYSlAHpyydBu6m4ZuRheBqTTUfRE/aG89DaqEyo.HPu';
        Date::setTestNow($now = Date::create(2020, 01, 01, 16, 30));

        DB::table('web_authn_recoveries')->insert([
            'email'      => $this->user->email,
            'token'      => $token,
            'created_at' => $now->toDateTimeString(),
        ]);

        $response = $this->json('POST', '/webauthn/recover/options', [
            'token' => 'test_token',
            'email' => $this->user->email,
        ])
        ->assertStatus(200);
    }


    /**
     * @test
     */
    public function test_options_with_invalid_token_returns_error()
    {
        $response = $this->json('POST', '/webauthn/recover/options', [
            'token' => 'myToken',
            'email' => $this->user->email,
        ])
        ->assertStatus(401);
    }


    /**
     * @test
     */
    public function test_options_without_inputs_returns_validation_errors()
    {
        $response = $this->json('POST', '/webauthn/recover/options', [
            'token' => '',
            'email' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token'])
            ->assertJsonValidationErrors(['email']);
    }


    /**
     * @test
     */
    // public function test_recover_returns_success()
    // {
    //     $token = '$2y$10$hgGTVVTRLsSYSlAHpyydBu6m4ZuRheBqTTUfRE/aG89DaqEyo.HPu';
    //     Date::setTestNow($now = Date::create(2020, 01, 01, 16, 30));

    //     DB::table('web_authn_recoveries')->insert([
    //         'email'      => $this->user->email,
    //         'token'      => $token,
    //         'created_at' => $now->toDateTimeString(),
    //     ]);

    //     $response = $this->json('POST', '/webauthn/recover', [], [
    //         'token' => $token,
    //         'email' => $this->user->email,
    //     ])
    //     ->assertStatus(200);
    // }


    /**
     * @test
     */
    public function test_recover_with_invalid_token_returns_validation_error()
    {
        $response = $this->json('POST', '/webauthn/recover', [], [
            'token' => 'toekn',
            'email' => $this->user->email,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
    }

}