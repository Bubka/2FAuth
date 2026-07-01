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
        if (! windows_os()) {
            foreach ([
                'oauth-public.key',
                'oauth-private.key',
            ] as $file) {
                $path = Passport::keyPath($file);

                if (file_exists($path)) {
                    $perms = substr(sprintf('%o', fileperms($path)), -4);

                    if ($perms != '0660' && $file === 'oauth-public.key') {
                        chmod($path, 0660);
                    } elseif ($perms != '0600' && $file === 'oauth-private.key') {
                        chmod($path, 0600);
                    }
                }
            }
        }

        return self::SUCCESS;
    }
}
