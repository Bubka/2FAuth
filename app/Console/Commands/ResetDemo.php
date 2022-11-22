<?php

namespace App\Console\Commands;

use App\Console\Commands\Utils\ResetTrait;
use Illuminate\Console\Command;

class ResetDemo extends Command
{
    use ResetTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:reset-demo {--no-confirm}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset 2FAuth with a fresh demo content';

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
        if (! config('2fauth.config.isDemoApp')) {
            $this->comment('2fauth:reset-demo can only run when isDemoApp option is On');

            return;
        }

        if ($this->option('no-confirm')) {
            $demo = 'demo';
        } else {
            $this->line('This will reset the app in order to run a clean and fresh demo.');
            $demo = $this->ask('To prevent any mistake please type the word "demo" to go on');
        }

        if ($demo === 'demo') {
            $this->resetIcons();
            $this->resetDB('DemoSeeder');
            $this->info('Demo app refreshed');
        } else {
            $this->comment('Bad confirmation word, nothing appened');
        }
    }
}
