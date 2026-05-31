<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\TwoFAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    use SeederTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'demo',
            'email' => 'demo@2fauth.app',
            'password' => Hash::make('demo'),
            'is_admin' => 1,
        ]);

        $groupSocialNetwork = $user->groups()->create([
            'name' => 'Social Networks',
        ]);

        $this->CreateTwoFAccountWithGroupAssignment($user, [
            'otp_type' => 'totp',
            'account' => 'johndoe@facebook.com',
            'service' => 'Facebook',
            'secret' => 'A4GRFTVVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'facebook.png',
            'legacy_uri' => 'otpauth://totp/Facebook:johndoe@facebook.com?secret=A4GRFTVVRBGY7UIW',
        ], $groupSocialNetwork);

        $this->CreateTwoFAccountWithGroupAssignment($user, [
            'otp_type' => 'totp',
            'service' => 'Twitter',
            'account' => '@john',
            'secret' => 'A2GRFTVVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'twitter.png',
            'legacy_uri' => 'otpauth://totp/Twitter:@john?secret=A2GRFTVVRBGY7UIW',
        ], $groupSocialNetwork);

        $this->CreateTwoFAccountWithGroupAssignment($user, [
            'otp_type' => 'totp',
            'service' => 'Instagram',
            'account' => '@johndoe',
            'secret' => 'A6GRFTVVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'instagram.png',
            'legacy_uri' => 'otpauth://totp/Instagram:@johndoe?secret=A6GRFTVVRBGY7UIW',
        ], $groupSocialNetwork);

        $this->CreateTwoFAccountWithGroupAssignment($user, [
            'otp_type' => 'totp',
            'service' => 'LinkedIn',
            'account' => '@johndoe',
            'secret' => 'A7GRFTVVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'linkedin.png',
            'legacy_uri' => 'otpauth://totp/LinkedIn:@johndoe?secret=A7GRFTVVRBGY7UIW',
        ], $groupSocialNetwork);

        $groupECommerce = $user->groups()->create([
            'name' => 'eCommerce',
        ]);

        $this->CreateTwoFAccountWithGroupAssignment($user, [
            'otp_type' => 'totp',
            'account' => 'johndoe',
            'service' => 'Amazon',
            'secret' => 'A7GRFTVVRBGY7UIW',
            'algorithm' => 'sha1',
            'digits' => 6,
            'period' => 30,
            'icon' => 'amazon.png',
            'legacy_uri' => 'otpauth://totp/Amazon:johndoe?secret=A7GRFTVVRBGY7UIW',
        ], $groupECommerce);

        $user->twofaccounts()->createMany([
            [
                'otp_type' => 'totp',
                'account' => 'john.doe@icloud.com',
                'service' => 'Apple',
                'secret' => 'A2GRFTVVRBGY7UIW',
                'algorithm' => 'sha1',
                'digits' => 6,
                'period' => 30,
                'icon' => 'apple.png',
                'legacy_uri' => 'otpauth://totp/Apple:john.doe@icloud.com?secret=A2GRFTVVRBGY7UIW',
            ],
            [
                'otp_type' => 'totp',
                'account' => 'john.doe',
                'service' => 'Dropbox',
                'secret' => 'A3GRFTVVRBGY7UIW',
                'algorithm' => 'sha1',
                'digits' => 6,
                'period' => 30,
                'icon' => 'dropbox.png',
                'legacy_uri' => 'otpauth://totp/Dropbox:john.doe?secret=A3GRFTVVRBGY7UIW',
            ],
            [
                'otp_type' => 'totp',
                'account' => '@john',
                'service' => 'Github',
                'secret' => 'A2GRFTVVRBGY7UIW',
                'algorithm' => 'sha1',
                'digits' => 6,
                'period' => 30,
                'icon' => 'github.png',
                'legacy_uri' => 'otpauth://totp/Github:@john?secret=A2GRFTVVRBGY7UIW',
            ],
            [
                'otp_type' => 'totp',
                'service' => 'Google',
                'account' => 'john.doe@gmail.com',
                'secret' => 'A5GRFTVVRBGY7UIW',
                'algorithm' => 'sha1',
                'digits' => 6,
                'period' => 30,
                'icon' => 'google.png',
                'legacy_uri' => 'otpauth://totp/Google:john.doe@gmail.com?secret=A5GRFTVVRBGY7UIW',
            ]
        ]);
    }
}
