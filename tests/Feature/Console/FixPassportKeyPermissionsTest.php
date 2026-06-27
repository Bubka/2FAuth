<?php

namespace Tests\Feature\Console;

use App\Console\Commands\FixPassportKeyPermissions;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(FixPassportKeyPermissions::class)]
class FixPassportKeyPermissionsTest extends FeatureTestCase
{
    protected string $keyDirectory;

    protected function setUp() : void
    {
        parent::setUp();

        $this->keyDirectory = storage_path('framework/testing/'.uniqid('passport-keys-', true));

        mkdir($this->keyDirectory, 0777, true);
        Passport::loadKeysFrom($this->keyDirectory);
    }

    protected function tearDown() : void
    {
        Passport::$keyPath = null;

        foreach ([
            'oauth-public.key',
            'oauth-private.key',
        ] as $file) {
            $path = $this->keyDirectory.DIRECTORY_SEPARATOR.$file;

            if (file_exists($path)) {
                unlink($path);
            }
        }

        if (is_dir($this->keyDirectory)) {
            rmdir($this->keyDirectory);
        }

        parent::tearDown();
    }

    #[Test]
    public function test_command_succeeds_when_keys_are_missing()
    {
        $this->artisan('2fauth:fix-passport-key-permissions')
            ->assertSuccessful();
    }

    #[Test]
    public function test_command_fixes_passport_key_permissions()
    {
        $publicKeyPath = $this->createKeyFile('oauth-public.key', 0644);
        $privateKeyPath = $this->createKeyFile('oauth-private.key', 0644);

        $this->artisan('2fauth:fix-passport-key-permissions')
            ->assertSuccessful();

        $this->assertSame('0660', $this->getPermissions($publicKeyPath));
        $this->assertSame('0600', $this->getPermissions($privateKeyPath));
    }

    protected function createKeyFile(string $filename, int $permissions) : string
    {
        $path = $this->keyDirectory.DIRECTORY_SEPARATOR.$filename;

        file_put_contents($path, 'test-key');
        chmod($path, $permissions);

        return $path;
    }

    protected function getPermissions(string $path) : string
    {
        return substr(sprintf('%o', fileperms($path)), -4);
    }
}