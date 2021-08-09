<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckDbConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:check-db-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if 2FAuth is connected to a database';

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
    public function handle() : int
    {
        $this->line('This will return the name of the connected database, otherwise false');

        try {
            DB::connection()->getPDO();
            $this->line(DB::connection()->getDatabaseName());
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }
}