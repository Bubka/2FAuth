<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /** @var \App\User */
    protected $user;


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
