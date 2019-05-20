<?php

use App\Account;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            Account::create([
                'name' => 'Firefly',
                'secret' => '3GB2I2P365J575LS',
            ]);
            Account::create([
                'name' => 'Facebook',
                'secret' => '3GB2I2P365J575LS',
            ]);
            Account::create([
                'name' => 'Twitter',
                'secret' => '3GB2I2P365J575LS',
            ]);
            Account::create([
                'name' => 'Google',
                'secret' => '3GB2I2P365J575LS',
            ]);
    }
}
