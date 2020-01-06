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
            'service' => $faker->unique()->domainName,
            'account' => $faker->email,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFTVVRBGY7UIW&issuer=test',
            'icon' => 'https://fakeimg.pl/64x64/'
        ]);
        TwoFAccount::create([
            'service' => $faker->unique()->domainName,
            'account' => $faker->email,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHYVRBGY7UIW&issuer=test',
            'icon' => 'https://fakeimg.pl/64x64/'
        ]);
        TwoFAccount::create([
            'service' => $faker->unique()->domainName,
            'account' => $faker->email,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
            'icon' => 'https://fakeimg.pl/64x64/'
        ]);
        TwoFAccount::create([
            'service' => $faker->unique()->domainName,
            'account' => $faker->email,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW&issuer=test',
            'icon' => 'https://fakeimg.pl/64x64/'
        ]);
        TwoFAccount::create([
            'service' => $faker->unique()->domainName,
            'account' => $faker->email,
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVOBGY7UIW&issuer=test',
            'icon' => 'https://fakeimg.pl/64x64/'
        ]);
    }
}