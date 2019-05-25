<?php

use App\TwoFAccount;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class TwoFAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'secret' => $faker->password,
        ]);

        $deletedResource = TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'secret' => $faker->password,
        ]);
        $deletedResource->delete();

        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'secret' => $faker->password,
        ]);
        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'secret' => $faker->password,
        ]);
        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'secret' => $faker->password,
        ]);
    }
}
