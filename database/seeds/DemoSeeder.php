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
            'service' => 'Amazon',
            'account' => 'johndoe',
            'uri' => 'otpauth://totp/johndoe@amazon.com?secret=A7GRFTVVRBGY7UIW&issuer=amazon',
            'icon' => 'amazon.png'
        ]);

        TwoFAccount::create([
            'service' => 'Apple',
            'account' => 'john.doe@icloud.com',
            'uri' => 'otpauth://totp/john@apple.com?secret=A2GRFTVVRBGY7UIW&issuer=apple',
            'icon' => 'apple.png'
        ]);

        TwoFAccount::create([
            'service' => 'Dropbox',
            'account' => 'john.doe',
            'uri' => 'otpauth://totp/johndoe@dropbox.com?secret=A3GRFTVVRBGY7UIW&issuer=dropbox',
            'icon' => 'dropbox.png'
        ]);

        TwoFAccount::create([
            'service' => 'Facebook',
            'account' => 'johndoe@facebook.com',
            'uri' => 'otpauth://totp/johndoe@facebook.com?secret=A4GRFTVVRBGY7UIW&issuer=facebook',
            'icon' => 'facebook.png'
        ]);

        TwoFAccount::create([
            'service' => 'Github',
            'account' => '@john',
            'uri' => 'otpauth://totp/johndoe@github.com?secret=A2GRFTVVRBGY7UIW&issuer=github',
            'icon' => 'github.png'
        ]);

        TwoFAccount::create([
            'service' => 'Google',
            'account' => 'john.doe@gmail.com',
            'uri' => 'otpauth://totp/johndoe@google.com?secret=A5GRFTVVRBGY7UIW&issuer=google',
            'icon' => 'google.png'
        ]);

        TwoFAccount::create([
            'service' => 'Instagram',
            'account' => '@johndoe',
            'uri' => 'otpauth://totp/johndoe@instagram.com?secret=A6GRFTVVRBGY7UIW&issuer=instagram',
            'icon' => 'instagram.png'
        ]);

        TwoFAccount::create([
            'service' => 'LinkedIn',
            'account' => '@johndoe',
            'uri' => 'otpauth://totp/johndoe@linkedin.com?secret=A7GRFTVVRBGY7UIW&issuer=linkedin',
            'icon' => 'linkedin.png'
        ]);

        TwoFAccount::create([
            'service' => 'Twitter',
            'account' => '@john',
            'uri' => 'otpauth://totp/johndoe@twitter.com?secret=A2GRFTVVRBGY7UIW&issuer=twitter',
            'icon' => 'twitter.png'
        ]);
    }
}
