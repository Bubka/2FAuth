<?php

namespace Tests\Feature\Console;

use App\Console\Commands\Install;
use Jackiedo\DotenvEditor\DotenvEditor;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;

/**
 * InstallTest test class
 */
#[CoversClass(Install::class)]
class InstallTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function test_install_completes()
    {
        $this->artisan('2fauth:install')
            ->expectsConfirmation('Existing .env file found. Do you wish to review its vars?', 'no')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_install_informs_about_no_interaction()
    {
        $this->artisan('2fauth:install', ['--no-interaction' => true])
            ->expectsOutput('(Running in no-interaction mode)')
            ->expectsConfirmation('Existing .env file found. Do you wish to review its vars?', 'no')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_install_generates_an_app_key()
    {
        config(['app.key' => '']);

        $this->assertEquals('', config('app.key'));

        $this->artisan('2fauth:install')
            ->expectsConfirmation('Existing .env file found. Do you wish to review its vars?', 'no')
            ->assertSuccessful();

        $this->assertNotEquals('', config('app.key'));
    }

    /**
     * @test
     */
    public function test_install_gives_2fauth_address()
    {
        $this->artisan('2fauth:install')
            ->expectsConfirmation('Existing .env file found. Do you wish to review its vars?', 'no')
            ->expectsOutputToContain(config('app.url'))
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_install_informs_about_sponsoring()
    {
        $this->artisan('2fauth:install')
            ->expectsConfirmation('Existing .env file found. Do you wish to review its vars?', 'no')
            ->expectsOutputToContain('https://ko-fi.com/bubka')
            ->expectsOutputToContain('https://github.com/sponsors/Bubka')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_install_fails_with_exception_message()
    {
        $mock = $this->mock(DotenvEditor::class);
        $mock->shouldReceive('load')
            ->andThrow(new \Exception('exception message'));

        $this->artisan('2fauth:install')
            ->expectsOutputToContain('exception message')
            ->assertFailed();
    }

    /**
     * @test
     */
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
