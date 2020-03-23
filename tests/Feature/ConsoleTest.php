<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class ConsoleTest extends TestCase
{

    /**
     * Test 2fauth:reset-demo console command.
     *
     * @return void
     */
    public function test2fauthResetDemowithoutDemoModeConsoleCommand()
    {
        $this->artisan('2fauth:reset-demo')
             ->expectsOutput('2fauth:reset-demo can only run when isDemoApp option is On')
             ->assertExitCode(0);
    }

    /**
     * Test 2fauth:reset-demo console command.
     *
     * @return void
     */
    public function test2fauthResetDemowithConfirmConsoleCommand()
    {
        Config::set('app.options.isDemoApp', true);

        $this->artisan('2fauth:reset-demo')
             ->expectsOutput('This will reset the app in order to run a clean and fresh demo.')
             ->expectsQuestion('To prevent any mistake please type the word "demo" to go on', 'demo')
             ->expectsOutput('Demo app refreshed')
             ->assertExitCode(0);

        $user = User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/1')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Amazon',
                'icon' => 'amazon.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/2')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Apple',
                'icon' => 'apple.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/3')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Dropbox',
                'icon' => 'dropbox.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/4')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Facebook',
                'icon' => 'facebook.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/5')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Github',
                'icon' => 'github.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/6')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Google',
                'icon' => 'google.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/7')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Instagram',
                'icon' => 'instagram.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/8')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'LinkedIn',
                'icon' => 'linkedin.png',
            ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/9')
            ->assertStatus(200)
            ->assertJson([
                'service' => 'Twitter',
                'icon' => 'twitter.png',
            ]);
    }

    /**
     * Test 2fauth:reset-demo console command.
     *
     * @return void
     */
    public function test2fauthResetDemowithBadConfirmationConsoleCommand()
    {
        Config::set('app.options.isDemoApp', true);

        $this->artisan('2fauth:reset-demo')
             ->expectsQuestion('To prevent any mistake please type the word "demo" to go on', 'null')
             ->expectsOutput('Bad confirmation word, nothing appened')
             ->assertExitCode(0);
    }


    /**
     * Test 2fauth:reset-demo console command.
     *
     * @return void
     */
    public function test2fauthResetDemowithoutConfirmationConsoleCommand()
    {
        Config::set('app.options.isDemoApp', true);

        $this->artisan('2fauth:reset-demo --no-confirm')
             ->expectsOutput('Demo app refreshed')
             ->assertExitCode(0);
    }

}