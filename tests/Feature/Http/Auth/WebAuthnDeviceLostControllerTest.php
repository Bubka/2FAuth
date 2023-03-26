<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use App\Notifications\WebauthnRecoveryNotification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\WebAuthnDeviceLostController
 * @covers  \App\Notifications\WebauthnRecoveryNotification
 * @covers  \App\Extensions\WebauthnCredentialBroker
 * @covers  \App\Http\Requests\WebauthnDeviceLostRequest
 * @covers  \App\Providers\AuthServiceProvider
 */
class WebAuthnDeviceLostControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     *
     * @covers  \App\Models\Traits\WebAuthnManageCredentials
     */
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

        $this->assertDatabaseHas('webauthn_recoveries', [
            'email' => $this->user->email,
        ]);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
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

        $this->assertDatabaseMissing('webauthn_recoveries', [
            'email' => 'bad@email.com',
        ]);
    }

    /**
     * @test
     */
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

        $this->assertDatabaseMissing('webauthn_recoveries', [
            'email' => 'bad@email.com',
        ]);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
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

        $this->assertDatabaseHas('webauthn_recoveries', [
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

    /**
     * @test
     */
    public function test_error_if_no_broker_is_set()
    {
        $this->app['config']->set('auth.passwords.webauthn', null);

        $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ])
            ->assertStatus(500);
    }
}
