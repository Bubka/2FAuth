<?php

namespace Tests\Feature\Http\Auth;

use App\Extensions\WebauthnCredentialBroker;
use App\Http\Controllers\Auth\WebAuthnDeviceLostController;
use App\Http\Requests\WebauthnDeviceLostRequest;
use App\Models\User;
use App\Notifications\WebauthnRecoveryNotification;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * WebAuthnDeviceLostControllerTest test class
 */
#[CoversMethod(User::class, 'sendWebauthnRecoveryNotification')]
#[CoversClass(WebAuthnDeviceLostController::class)]
#[CoversClass(WebauthnRecoveryNotification::class)]
#[CoversClass(WebauthnCredentialBroker::class)]
#[CoversClass(WebauthnDeviceLostRequest::class)]
#[CoversClass(AuthServiceProvider::class)]
class WebAuthnDeviceLostControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_send_recovery_email_sends_notification_on_success()
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
    public function test_send_recovery_email_returns_same_response_for_unknown_email()
    {
        Notification::fake();

        $known = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        $unknown = $this->json('POST', '/webauthn/lost', [
            'email' => 'bad@email.com',
        ]);

        $unknown->assertStatus(200);
        $this->assertSame($known->getContent(), $unknown->getContent());
        Notification::assertSentTimes(WebauthnRecoveryNotification::class, 1);

        $this->assertDatabaseMissing(config('auth.passwords.webauthn.table'), [
            'email' => 'bad@email.com',
        ]);
    }

    #[Test]
    public function test_send_recovery_email_does_not_send_anything_to_invalid_email()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => 'not-an-email',
        ]);

        Notification::assertNothingSent();

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    #[Test]
    public function test_send_recovery_email_when_throttled_returns_same_response()
    {
        Notification::fake();

        $first = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        $first->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertDatabaseHas(config('auth.passwords.webauthn.table'), [
            'email' => $this->user->email,
        ]);

        $second = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        $second->assertStatus(200);
        $this->assertSame($first->getContent(), $second->getContent());
        Notification::assertSentTimes(WebauthnRecoveryNotification::class, 1);
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
