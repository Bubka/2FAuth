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
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
        ]);

        $deletedResource = TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
        ]);
        $deletedResource->delete();

        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
        ]);
        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
        ]);
        TwoFAccount::create([
            'name' => $faker->unique()->domainName,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
        ]);
    }
}
