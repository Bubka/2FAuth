<?php

namespace Tests\Feature\Http\Auth;

use App\Extensions\WebauthnCredentialBroker;
use App\Http\Controllers\Auth\WebAuthnDeviceLostController;
use App\Http\Requests\WebauthnDeviceLostRequest;
use App\Models\User;
use App\Notifications\WebauthnRecoveryNotification;
use App\Providers\AuthServiceProvider;
use App\Rules\CaseInsensitiveEmailExists;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * WebAuthnDeviceLostControllerTest test class
 */
#[CoversClass(WebAuthnDeviceLostController::class)]
#[CoversClass(WebauthnRecoveryNotification::class)]
#[CoversClass(WebauthnCredentialBroker::class)]
#[CoversClass(WebauthnDeviceLostRequest::class)]
#[CoversClass(AuthServiceProvider::class)]
#[CoversMethod(CaseInsensitiveEmailExists::class, 'validate')]
class WebAuthnDeviceLostControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_sendRecoveryEmail_sends_notification_on_success()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        Notification::assertSentTo($this->user, WebauthnRecoveryNotification::class);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertDatabaseHas(config('auth.passwords.webauthn.table'), [
            'email' => $this->user->email,
        ]);
    }

    #[Test]
    public function test_WebauthnRecoveryNotification_renders_to_email()
    {
        $mail = (new WebauthnRecoveryNotification('test_token'))->toMail($this->user)->render();

        $this->assertStringContainsString(
            'http://localhost/webauthn/recover?token=test_token&amp;email=' . urlencode($this->user->email),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get('Recover Account'),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get(
                'You are receiving this email because we received an account recovery request for your account.'
            ),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get(
                'This recovery link will expire in :count minutes.',
                ['count' => config('auth.passwords.webauthn.expire')]
            ),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get('If you did not request an account recovery, no further action is required.'),
            $mail
        );
    }

    #[Test]
    public function test_sendRecoveryEmail_does_not_send_anything_to_unknown_email()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => 'bad@email.com',
        ]);

        Notification::assertNothingSent();

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);

        $this->assertDatabaseMissing(config('auth.passwords.webauthn.table'), [
            'email' => 'bad@email.com',
        ]);
    }

    #[Test]
    public function test_sendRecoveryEmail_does_not_send_anything_to_invalid_email()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => 'bad@email.com',
        ]);

        Notification::assertNothingSent();

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);

        $this->assertDatabaseMissing(config('auth.passwords.webauthn.table'), [
            'email' => 'bad@email.com',
        ]);
    }

    #[Test]
    public function test_sendRecoveryEmail_does_not_send_anything_to_not_WebAuthnAuthenticatable()
    {
        $mock = $this->mock(\App\Extensions\WebauthnCredentialBroker::class)->makePartial();
        $mock->shouldReceive('getUser')
            ->andReturn(new \Illuminate\Foundation\Auth\User());

        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        Notification::assertNothingSent();

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    #[Test]
    public function test_sendRecoveryEmail_is_throttled()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        Notification::assertSentTo($this->user, WebauthnRecoveryNotification::class);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertDatabaseHas(config('auth.passwords.webauthn.table'), [
            'email' => $this->user->email,
        ]);

        $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrorfor('email')
            ->assertJsonFragment([
                'message' => __('passwords.throttled'),
            ]);
    }

    #[Test]
    public function test_error_if_no_broker_is_set()
    {
        $this->app['config']->set('auth.passwords.webauthn', null);

        $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ])
            ->assertStatus(500);
    }
}
