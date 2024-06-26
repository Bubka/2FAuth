<?php

namespace Tests\Feature\Console;

use App\Console\Commands\Install;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\DotenvEditor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * InstallTest test class
 */
#[CoversClass(Install::class)]
class InstallTest extends FeatureTestCase
{
    const PASSPORT_PENDING_MIGRATIONS_CONFIRMATION = 'Would you like to run all pending database migrations?';

    const PASSPORT_CREATE_CLIENTS_CONFIRMATION = 'Would you like to create the "personal access" and "password grant" clients?';

    const TWOFAUTH_REVIEW_ENV_VAR_CONFIRMATION = 'Existing .env file found. Do you wish to review its vars?';
    
    #[Test]
    public function test_install_completes()
    {
        $this->artisan('2fauth:install')
            ->expectsConfirmation(self::TWOFAUTH_REVIEW_ENV_VAR_CONFIRMATION, 'no')
            // 2 following confirmations have been introduced with Passport v12 and its auto-publishing
            // migrations feature. Even if the '2fauth:install' command runs 'passport:install'
            // silently, the 2 confirmations are triggered and needs to be handled in tests.
            ->expectsConfirmation(self::PASSPORT_PENDING_MIGRATIONS_CONFIRMATION, 'yes')
            ->expectsConfirmation(self::PASSPORT_CREATE_CLIENTS_CONFIRMATION, 'yes')
            ->assertSuccessful();
    }

    #[Test]
    public function test_install_informs_about_no_interaction()
    {
        $this->artisan('2fauth:install', ['--no-interaction' => true])
            ->expectsOutput('(Running in no-interaction mode)')
            ->expectsConfirmation(self::TWOFAUTH_REVIEW_ENV_VAR_CONFIRMATION, 'no')
            ->expectsConfirmation(self::PASSPORT_PENDING_MIGRATIONS_CONFIRMATION, 'yes')
            ->expectsConfirmation(self::PASSPORT_CREATE_CLIENTS_CONFIRMATION, 'yes')
            ->assertSuccessful();
    }

    #[Test]
    public function test_install_generates_an_app_key()
    {
        config(['app.key' => '']);

        $this->assertEquals('', config('app.key'));

        $this->artisan('2fauth:install')
            ->expectsConfirmation(self::TWOFAUTH_REVIEW_ENV_VAR_CONFIRMATION, 'no')
            ->expectsConfirmation(self::PASSPORT_PENDING_MIGRATIONS_CONFIRMATION, 'yes')
            ->expectsConfirmation(self::PASSPORT_CREATE_CLIENTS_CONFIRMATION, 'yes')
            ->assertSuccessful();

        $this->assertNotEquals('', config('app.key'));
    }

    #[Test]
    public function test_install_gives_2fauth_address()
    {
        $this->artisan('2fauth:install')
            ->expectsConfirmation(self::TWOFAUTH_REVIEW_ENV_VAR_CONFIRMATION, 'no')
            ->expectsConfirmation(self::PASSPORT_PENDING_MIGRATIONS_CONFIRMATION, 'yes')
            ->expectsConfirmation(self::PASSPORT_CREATE_CLIENTS_CONFIRMATION, 'yes')
            ->expectsOutputToContain(config('app.url'))
            ->assertSuccessful();
    }

    #[Test]
    public function test_install_informs_about_sponsoring()
    {
        $this->artisan('2fauth:install')
            ->expectsConfirmation(self::TWOFAUTH_REVIEW_ENV_VAR_CONFIRMATION, 'no')
            ->expectsConfirmation(self::PASSPORT_PENDING_MIGRATIONS_CONFIRMATION, 'yes')
            ->expectsConfirmation(self::PASSPORT_CREATE_CLIENTS_CONFIRMATION, 'yes')
            ->expectsOutputToContain('https://ko-fi.com/bubka')
            ->expectsOutputToContain('https://github.com/sponsors/Bubka')
            ->assertSuccessful();
    }

    #[Test]
    public function test_install_fails_with_exception_message()
    {
        $mock = $this->mock(DotenvEditor::class);
        $mock->shouldReceive('load')
            ->andThrow(new \Exception('exception message'));

        $this->artisan('2fauth:install')
            ->expectsOutputToContain('exception message')
            ->assertFailed();
    }

    #[Test]
    public function test_install_fails_with_link_to_online_help()
    {
        $mock = $this->mock(DotenvEditor::class);
        $mock->shouldReceive('load')
            ->andThrow(new \Exception());

        $this->artisan('2fauth:install')
            ->expectsOutputToContain(config('2fauth.installDocUrl'))
            ->assertFailed();
    }
}
