<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\TwoFAccount;
use Faker\Generator as Faker;

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

    $service = $faker->unique()->domainName;
    $account = $faker->safeEmail;

    return [
        'service' => $service,
        'account' => $account,
        'uri' => 'otpauth://totp/' . $service . ':' . $account . '?secret=' . $faker->regexify('[A-Z0-9]{16}') . '&issuer=' . $service,
        'icon' => '',
    ];
});
