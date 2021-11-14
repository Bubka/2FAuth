<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\TwoFAccount;
use Faker\Generator as Faker;
use ParagonIE\ConstantTime\Base32;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(TwoFAccount::class, function (Faker $faker) {

    $account = $faker->safeEmail;
    $service = $faker->unique()->domainName;
    $secret = Base32::encodeUpper($faker->regexify('[A-Z0-9]{8}'));

    return [
        'otp_type' => 'totp',
        'account' => $account,
        'service' => $service,
        'secret' => $secret,
        'algorithm' => 'sha1',
        'digits' => 6,
        'period' => 30,
        'legacy_uri' => 'otpauth://hotp/' . $service . ':' . $account . '?secret=' . $secret . '&issuer=' . $service,
        'icon' => '',
    ];
});