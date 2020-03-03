<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
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

        //$this->expectsNotification($this->user, ResetPassword::class);

        $response = $this->json('POST', '/api/password/email', [
            'email' => $this->user->email
        ]);

        $response->assertStatus(200);

        $token = \Illuminate\Support\Facades\DB::table('password_resets')->first();
        $this->assertNotNull($token);

        // Notification::assertSentTo($this->user, ResetPassword::class, function ($notification, $channels) use ($token) {
        //     return Hash::check($notification->token, $token->token) === true;
        // });
    }



    /**
     * Testing submitting the reset password  without
     * email address.
     */
    public function testSubmitResetPasswordWithoutInput()
    {
        $response = $this->json('POST', '/api/password/reset', [
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'token' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password', 'token']);
    }

    /**
     * Testing submitting the reset password with
     * invalid input.
     */
    public function testSubmitResetPasswordWithInvalidInput()
    {
        $response = $this->json('POST', '/api/password/reset', [
            'email' => 'qsdqsdqsd',
            'password' => 'foofoofoo',
            'password_confirmation' => 'barbarbar',
            'token' => 'token'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Testing submitting the reset password with
     * invalid input.
     */
    public function testSubmitResetPasswordWithTooShortPasswords()
    {
        $response = $this->json('POST', '/api/password/reset', [
            'email' => 'foo@bar.com',
            'password' => 'foo',
            'password_confirmation' => 'foo',
            'token' => 'token'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    /**
     * Testing submitting the rest password.
     */
    public function testSubmitResetPassword()
    {
        Notification::fake();

        $this->user = factory(User::class)->create([
            'name' => 'user',
            'email' => 'user@example.org',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);

        $token = Password::broker()->createToken($this->user);

        $response = $this->json('POST', '/api/password/reset', [
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' =>  $token
        ]);

        $this->user->refresh();

        $response->assertStatus(200);
        $this->assertTrue(Hash::check('newpassword', $this->user->password));

    }

}
