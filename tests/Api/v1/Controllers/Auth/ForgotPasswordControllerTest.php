<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;

class ForgotPasswordControllerTest extends FeatureTestCase
{
    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @test
     */
    public function test_submit_email_password_request_without_email_returns_validation_error()
    {
        $response = $this->json('POST', '/api/v1/user/password/lost', [
            'email' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_with_invalid_email_returns_validation_error()
    {
        $response = $this->json('POST', '/api/v1/user/password/lost', [
            'email' => 'nametest.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_with_unknown_email_returns_validation_error()
    {
        $response = $this->json('POST', '/api/v1/user/password/lost', [
            'email' => 'name@test.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_returns_success()
    {
        Notification::fake();

        $this->user = factory(User::class)->create();

        $response = $this->json('POST', '/api/v1/user/password/lost', [
            'email' => $this->user->email
        ]);

        $response->assertStatus(200);

        $token = \Illuminate\Support\Facades\DB::table('password_resets')->first();
        $this->assertNotNull($token);

        Notification::assertSentTo($this->user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_in_demo_mode_returns_unauthorized()
    {
        Config::set('2fauth.config.isDemoApp', true);

        $response = $this->json('POST', '/api/v1/user/password/lost', [
            'email' => ''
        ]);

        $response->assertStatus(401);
    }

}