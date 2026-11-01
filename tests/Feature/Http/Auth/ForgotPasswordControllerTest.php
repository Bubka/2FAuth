<?php

namespace Tests\Feature\Http\Auth;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Middleware\RejectIfAuthenticated;
use App\Http\Middleware\RejectIfDemoMode;
use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * ForgotPasswordControllerTest test class
 */
#[CoversClass(ForgotPasswordController::class)]
#[CoversClass(User::class)]
#[CoversClass(RejectIfDemoMode::class)]
#[CoversClass(RejectIfAuthenticated::class)]
class ForgotPasswordControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    #[Test]
    public function test_submit_email_password_request_without_email_returns_validation_error()
    {
        $response = $this->json('POST', '/user/password/lost', [
            'email' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function test_submit_email_password_request_with_invalid_email_returns_validation_error()
    {
        $response = $this->json('POST', '/user/password/lost', [
            'email' => 'nametest.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function test_submit_email_password_request_with_unknown_email_returns_same_response_as_known_email()
    {
        Notification::fake();

        $user = User::factory()->create();

        $known = $this->json('POST', '/user/password/lost', [
            'email' => $user->email,
        ]);

        $unknown = $this->json('POST', '/user/password/lost', [
            'email' => 'name@test.com',
        ]);

        $unknown->assertStatus(200);
        $this->assertSame($known->getContent(), $unknown->getContent());
        Notification::assertSentTimes(ResetPassword::class, 1);
    }

    #[Test]
    public function test_submit_email_password_request_when_throttled_returns_same_response()
    {
        Notification::fake();

        $user = User::factory()->create();

        $first  = $this->json('POST', '/user/password/lost', ['email' => $user->email]);
        $second = $this->json('POST', '/user/password/lost', ['email' => $user->email]);

        $second->assertStatus(200);
        $this->assertSame($first->getContent(), $second->getContent());
        Notification::assertSentTimes(ResetPassword::class, 1);
    }

    #[Test]
    public function test_submit_email_password_request_returns_success()
    {
        Notification::fake();

        $this->user = User::factory()->create();

        $response = $this->json('POST', '/user/password/lost', [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(200);

        $token = \Illuminate\Support\Facades\DB::table(config('auth.passwords.users.table'))->first();
        $this->assertNotNull($token);

        Notification::assertSentTo($this->user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    #[Test]
    public function test_submit_email_password_request_in_demo_mode_returns_unauthorized()
    {
        Config::set('2fauth.config.isDemoApp', true);

        $response = $this->json('POST', '/user/password/lost', [
            'email' => '',
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function test_submit_email_password_request_when_authenticated_returns_ok()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'web-guard')
            ->json('POST', '/user/password/lost', [
                'email' => $user->email,
            ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
