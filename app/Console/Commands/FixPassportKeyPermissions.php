<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\Passport;

class FixPassportKeyPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:fix-passport-key-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix key permissions for Passport keys';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        if (windows_os()) {
            return self::SUCCESS;
        }

        $hasIssues           = false;
        $expectedPermissions = [
            'oauth-public.key'  => 0660,
            'oauth-private.key' => 0600,
        ];

        foreach ($expectedPermissions as $file => $expectedPermission) {
            $path = Passport::keyPath($file);

            if (! file_exists($path)) {
                $this->warn("Passport key file {$file} was not found at {$path}");
                $hasIssues = true;

                continue;
            }

            try {
                $permissions = fileperms($path);

                if ($permissions === false) {
                    $this->warn("Unable to read permissions for {$file} at {$path}");
                    $hasIssues = true;

                    continue;
                }

                $currentPermissions          = substr(sprintf('%o', $permissions), -4);
                $expectedPermissionsAsString = sprintf('%04o', $expectedPermission);

                if ($currentPermissions === $expectedPermissionsAsString) {
                    continue;
                }

                if (! chmod($path, $expectedPermission)) {
                    $this->warn("Failed to set {$expectedPermissionsAsString} on {$file} at {$path}");
                    $hasIssues = true;
                }
            } catch (\Throwable $e) {
                $this->error("Failed to fix permissions for {$file}: " . $e->getMessage());
                $hasIssues = true;
            }
        }

        return $hasIssues ? self::FAILURE : self::SUCCESS;
    }
}
