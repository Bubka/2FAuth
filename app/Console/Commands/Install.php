<?php
/**
 * 
 * The MIT License (MIT)
 * Copyright (c) 2023 Bubka
 * Copyright (c) 2018 Phan An (https://github.com/koel/koel/blob/master/app/Console/Commands/InitCommand.php)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Connection;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\SQLiteDatabaseDoesNotExistException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Jackiedo\DotenvEditor\DotenvEditor;
use PDOException;
use Throwable;

class Install extends Command
{
    use ConfirmableTrait;

    /**
     * Identify if we start with an existing .env file
     *
     * @var bool
     */
    protected $envFileExists = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:install {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run 2FAuth installation or update';

    /**
     * Create a new command instance.
     *
     * @param  \Jackiedo\DotenvEditor\DotenvEditor  $dotenvEditor
     * @return void
     */
    public function __construct(
        protected DotenvEditor $dotenvEditor,
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->newLine(2);
        $this->alert('2FAuth installation');

        if ($this->option('no-interaction')) {
            $this->newLine();
            $this->info('(Running in no-interaction mode)');
            $this->newLine();
        }

        $this->info('Start processing');
        try {
            $this->clearCaches();
            $this->loadEnvFile();
            $this->maybeGenerateAppKey();

            if ((! $this->envFileExists || $this->confirm('Existing .env file found. Do you wish to review its vars?', true)) && ! $this->option('no-interaction')) {
                $this->setMainEnvVars();
                $this->setDbEnvVars();
            }

            $this->migrateDatabase();
            $this->installPassport();
            $this->createStorageLink();
            $this->cacheConfig();

            $this->dotenvEditor->save();
        } catch (Throwable $e) {
            Log::error($e);

            $this->newLine();
            $this->line('Sorry, something went wrong :(');
            $this->newLine();
            $this->components->error($e->getMessage());
            $this->components->info('See the error log at storage/logs/laravel.log for the full stack trace.');
            
            $this->newLine();
            $this->line('Fix the error and rerun the \'2fauth:install\' command to complete installation.');
            $this->newLine();
            $this->line('As a reminder, you can always install/upgrade manually following the guide at:');
            $this->info(config('2fauth.installDocUrl'));
            $this->newLine();
            $this->line('You can also ask for some help at:');
            $this->info(config('2fauth.repository') . '/issues');

            return self::FAILURE;
        }

        $this->newLine();
        $this->output->success('Installation complete successfully');
        $this->line('Visit <info>' . config('app.url') . '</info> to start using 2FAuth');
        $this->newLine();
        $this->line('-----------------------------------');
        $this->line('.▀█▀.█▄█.█▀█.█▄.█.█▄▀  █▄█.█▀█.█─█');
        $this->line('─.█.─█▀█.█▀█.█.▀█.█▀▄  ─█.─█▄█.█▄█ for using 2FAuth');
        $this->newLine();
        $this->line('Want to support its development?');
        $this->line('You can Buy me a coffee => <info>https://ko-fi.com/bubka</info>');

        return self::SUCCESS;
    }

    /**
     * 
     */
    protected function installPassport() : void
    {
        $this->components->task('Setting up Passport', function () : void {
            $this->callSilently('passport:install');
        });
    }

    /**
     * 
     */
    protected function cacheConfig() : void
    {
        $this->components->task('Caching config', function () : void {
            $this->callSilently('config:cache');
        });
    }

    /**
     * 
     */
    protected function createStorageLink() : void
    {
        if (!file_exists(public_path('storage'))) {
            $this->components->task('Creating storage link', function () : void {
                $this->callSilently('storage:link');
            });
        }
    }

    /**
     * 
     */
    protected function setMainEnvVars() : void
    {
        while (true) {
            $appUrl = trim($this->ask('URL of this 2FAuth instance', config('app.url')), '/');
            if (filter_var($appUrl, FILTER_VALIDATE_URL)) {
                break;
            }
            else {
                $this->components->error('This is not a valid URL, please retry');
            }
        }

        $urlPath = parse_url($appUrl, PHP_URL_PATH);

        if ($urlPath && $urlPath != '/') {
            $urlPath = trim($urlPath, '/');
            $this->components->info('2Fauth will be served under subdirectory /' . $urlPath);
            $this->dotenvEditor->setKey('APP_SUBDIRECTORY', $urlPath);
        }

        $this->dotenvEditor->setKey('APP_URL', $appUrl);
    }


    /**
     * Prompt user for valid database credentials and set them to .env file.
     */
    protected function setDbEnvVars() : void
    {
        $config = [
            'DB_HOST'     => '',
            'DB_PORT'     => '',
            'DB_USERNAME' => '',
            'DB_PASSWORD' => '',
        ];

        $config['DB_CONNECTION'] = $this->choice(
            'Type of database',
            [
                'mysql'  => 'MySQL/MariaDB',
                'pgsql'  => 'PostgreSQL',
                'sqlsrv' => 'SQL Server',
                'sqlite' => 'SQLite',
            ],
            config('database.default')
        );

        if ($config['DB_CONNECTION'] === 'sqlite') {
            $databasePath = $this->dotenvEditor->getValue('DB_CONNECTION') != 'sqlite'
                ? database_path('database.sqlite')
                : config('database.connections.sqlite.database');

            $config['DB_DATABASE'] = $this->ask('Absolute path to the DB file', $databasePath);
        } else {
            $defaultName = $this->dotenvEditor->getValue('DB_DATABASE') ?: '2fauth';
            $databaseName = $this->dotenvEditor->getValue('DB_CONNECTION') == 'sqlite'
                ? '2fauth'
                : $defaultName;

            $config['DB_HOST']     = $this->ask('Database host', config('database.connections.' . $config['DB_CONNECTION'] . '.host'));
            $config['DB_PORT']     = (string) $this->ask('Database port', config('database.connections.' . $config['DB_CONNECTION'] . '.port'));
            $config['DB_DATABASE'] = $this->ask('Database name', $databaseName);
            $config['DB_USERNAME'] = $this->ask('Database user', config('database.connections.' . $config['DB_CONNECTION'] . '.username'));
            $config['DB_PASSWORD'] = (string) $this->secret('Database password', config('database.connections.' . $config['DB_CONNECTION'] . '.password'));
            // $config['DB_PASSWORD'] = (string) $this->secret('Database password', true);
        }

        $this->dotenvEditor->setKeys($config);
        $this->dotenvEditor->save();

        // Set the config so that the next DB attempt uses refreshed credentials
        config([
            'database.default' => $config['DB_CONNECTION'],
            'database.connections.' . $config['DB_CONNECTION'] . '.database' => $config['DB_DATABASE'],
            'database.connections.' . $config['DB_CONNECTION'] . '.host'     => $config['DB_HOST'],
            'database.connections.' . $config['DB_CONNECTION'] . '.port'     => $config['DB_PORT'],
            'database.connections.' . $config['DB_CONNECTION'] . '.username' => $config['DB_USERNAME'],
            'database.connections.' . $config['DB_CONNECTION'] . '.password' => $config['DB_PASSWORD'],
        ]);
        $this->laravel['db']->purge();
    }

    /**
     * 
     */
    protected function migrateDatabase() : mixed
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        return $this->call('migrate', ['--force' => $this->option('force')]);
    }

    /**
     * 
     */
    protected function clearCaches() : void
    {
        $this->components->task('Clearing caches', function () : void {
            $this->callSilently('config:clear');
            $this->callSilently('cache:clear');
        });
    }

    /**
     * 
     */
    protected function loadEnvFile() : void
    {
        $this->envFileExists = file_exists(base_path('.env'));

        if (! $this->envFileExists && $this->option('no-interaction')) {
            throw new Exception('--no-interaction option cannot be used during first install');
        }

        if (! $this->envFileExists) {
            $this->input->setOption('force', true);
        }

        $this->components->task('Preparing .env file', static function () : void {
            if (!file_exists(base_path('.env'))) {
                copy(base_path('.env.example'), base_path('.env'));
            }
        });

        $this->dotenvEditor->load(base_path('.env'));
    }

    /**
     * 
     */
    protected function maybeGenerateAppKey() : void
    {
        $key = config('app.key');

        $this->components->task($key ? 'Retrieving app key' : 'Generating app key', function () use (&$key) : void {
            if (!$key) {
                // Generate the key manually to prevent some clashes with `php artisan key:generate`
                $key = $this->generateRandomKey();
                $this->dotenvEditor->setKey('APP_KEY', $key);
                config('app.key', $key);
            }
        });
    }

    /**
     * Generate a random key for the application.
     */
    protected function generateRandomKey() : string
    {
        return 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher')));
    }
}
