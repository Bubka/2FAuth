<?php

namespace Database\Seeders;

use App\Models\TwoFAccount;
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
        $faker = \Faker\Factory::create();

        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => $faker->safeEmail,
            'service' => $faker->unique()->domainName,
            'secret' => 'A4GRFHZVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'https://fakeimg.pl/64x64/',
        ]);
        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => $faker->safeEmail,
            'service' => $faker->unique()->domainName,
            'secret' => 'A4GRFHZVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'https://fakeimg.pl/64x64/',
        ]);
        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => $faker->safeEmail,
            'service' => $faker->unique()->domainName,
            'secret' => 'A4GRFHZVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'https://fakeimg.pl/64x64/',
        ]);
        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => $faker->safeEmail,
            'service' => $faker->unique()->domainName,
            'secret' => 'A4GRFHZVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'https://fakeimg.pl/64x64/',
        ]);
        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => $faker->safeEmail,
            'service' => $faker->unique()->domainName,
            'secret' => 'A4GRFHZVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'https://fakeimg.pl/64x64/',
        ]);
    }
}