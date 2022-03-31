<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Notification;

class WebAuthnDeviceLostControllerTest extends FeatureTestCase
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
    public function test_sendRecoveryEmail_sends_notification_on_success()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => $this->user->email,
        ]);

        Notification::assertSentTo($this->user, \DarkGhostHunter\Larapass\Notifications\AccountRecoveryNotification::class);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);
    }


    /**
     * @test
     */
    public function test_sendRecoveryEmail_does_not_send_anything_on_error()
    {
        Notification::fake();

        $response = $this->json('POST', '/webauthn/lost', [
            'email' => 'bad@email.com',
        ]);

        Notification::assertNothingSent();

        $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'email'
        ]);
    }

}