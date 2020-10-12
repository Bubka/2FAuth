<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /** @var \App\User */
    protected $user;

    /**
     * Testing submitting the email password request without
     * email address.
     */
    public function testSubmitEmailPasswordRequestWithoutEmail()
    {
        $response = $this->json('POST', '/api/password/email', [
            'email' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Testing submitting the email password request with an invalid
     * email address.
     */
    public function testSubmitEmailPasswordRequestWithInvalidEmail()
    {
        $response = $this->json('POST', '/api/password/email', [
            'email' => 'nametest.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Testing submitting the email password request with an unknown
     * email address.
     */
    public function testSubmitEmailPasswordRequestWithUnknownEmail()
    {
        $response = $this->json('POST', '/api/password/email', [
            'email' => 'name@test.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Testing submitting the email password request with a valid email address.
     */
    public function testSubmitEmailPasswordRequest()
    {
        Notification::fake();

        $this->user = factory(User::class)->create([
            'name' => 'user',
            'email' => 'user@example.org',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        $response = $this->json('POST', '/api/password/email', [
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
     * Testing submitting the email password request in Demo mode
     */
    public function testSubmitEmailPasswordRequestInDemoMode()
    {
        Config::set('app.options.isDemoApp', true);

        $response = $this->json('POST', '/api/password/email', [
            'email' => ''
        ]);

        $response->assertStatus(401);
    }

}