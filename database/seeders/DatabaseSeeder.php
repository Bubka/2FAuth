<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Seed Database with demo data
            DemoSeeder::class,
            // Seed Database with test data
            TestingSeeder::class
        ]);
    }
}
