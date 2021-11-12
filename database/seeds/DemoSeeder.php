<?php

use App\User;
use App\TwoFAccount;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'demo',
            'email' => 'demo@2fauth.app',
            'password' => bcrypt('demo'),
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => 'johndoe',
            'service' => 'Amazon',
            'secret' => 'A7GRFTVVRBGY7UIW',
            'icon' => 'amazon.png',
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => 'john.doe@icloud.com',
            'service' => 'Apple',
            'secret' => 'A2GRFTVVRBGY7UIW',
            'icon' => 'apple.png',
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => 'john.doe',
            'service' => 'Dropbox',
            'secret' => 'A3GRFTVVRBGY7UIW',
            'icon' => 'dropbox.png',
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => 'johndoe@facebook.com',
            'service' => 'Facebook',
            'secret' => 'A4GRFTVVRBGY7UIW',
            'icon' => 'facebook.png',
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'account' => '@john',
            'service' => 'Github',
            'secret' => 'A2GRFTVVRBGY7UIW',
            'icon' => 'github.png',
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'service' => 'Google',
            'account' => 'john.doe@gmail.com',
            'secret' => 'A5GRFTVVRBGY7UIW',
            'icon' => 'google.png'
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'service' => 'Instagram',
            'account' => '@johndoe',
            'secret' => 'A6GRFTVVRBGY7UIW',
            'icon' => 'instagram.png'
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'service' => 'LinkedIn',
            'account' => '@johndoe',
            'secret' => 'A7GRFTVVRBGY7UIW',
            'icon' => 'linkedin.png'
        ]);

        TwoFAccount::create([
            'otp_type' => 'totp',
            'service' => 'Twitter',
            'account' => '@john',
            'secret' => 'A2GRFTVVRBGY7UIW',
            'icon' => 'twitter.png'
        ]);
    }
}
