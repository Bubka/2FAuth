<?php

use App\TwoFAccount;
use Illuminate\Database\Seeder;

class TwoFAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            TwoFAccount::create([
                'name' => 'Firefly',
                'secret' => '3G2I2P36J57LS',
            ]);
            TwoFAccount::create([
                'name' => 'Facebook',
                'secret' => '3GB2I2P365J5S',
            ]);
            TwoFAccount::create([
                'name' => 'Twitter',
                'secret' => '3GB2I25J575LS',
            ]);
            TwoFAccount::create([
                'name' => 'Google',
                'secret' => '3GB2I25J575LS',
            ]);
    }
}
