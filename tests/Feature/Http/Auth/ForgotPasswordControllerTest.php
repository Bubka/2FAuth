<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\ForgotPasswordController
 * @covers  \App\Models\User
 * @covers  \App\Http\Middleware\RejectIfDemoMode
 * @covers  \App\Http\Middleware\RejectIfAuthenticated
 */
class ForgotPasswordControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @test
     */
    public function test_submit_email_password_request_without_email_returns_validation_error()
    {
        $response = $this->json('POST', '/user/password/lost', [
            'email' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_with_invalid_email_returns_validation_error()
    {
        $response = $this->json('POST', '/user/password/lost', [
            'email' => 'nametest.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_with_unknown_email_returns_validation_error()
    {
        $response = $this->json('POST', '/user/password/lost', [
            'email' => 'name@test.com',
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

        $this->user = User::factory()->create();

        $response = $this->json('POST', '/user/password/lost', [
            'email' => $this->user->email,
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

        $response = $this->json('POST', '/user/password/lost', [
            'email' => '',
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function test_submit_email_password_request_when_authenticated_returns_bad_request()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'web-guard')
            ->json('POST', '/user/password/lost', [
                'email' => $user->email,
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
