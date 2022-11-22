<?php

namespace App\Console\Commands;

use App\Console\Commands\Utils\ResetTrait;
use Illuminate\Console\Command;

class ResetTesting extends Command
{
    use ResetTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:reset-testing {--no-confirm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset 2FAuth with a fresh testing content';

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
     *
     * @return mixed
     */
    public function handle()
    {
        if (! config('2fauth.config.isTestingApp')) {
            $this->comment('2fauth:reset-testing can only run when isTestingApp option is On');

            return;
        }

        if ($this->option('no-confirm')) {
            $testing = 'testing';
        } else {
            $this->line('This will reset the app in order to run a clean and fresh testing app.');
            $testing = $this->ask('To prevent any mistake please type the word "testing" to go on');
        }

        if ($testing === 'testing') {
            $this->resetIcons();
            $this->resetDB('TestingSeeder');

            $this->info('Testing app refreshed');
        } else {
            $this->comment('Bad confirmation word, nothing appened');
        }
    }
}
