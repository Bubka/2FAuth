<?php

namespace Tests\Classes;

class OtpTestData
{
    const ACCOUNT = 'account';
    const SERVICE = 'service';
    const STEAM = 'Steam';
    const SECRET = 'A4GRFHVVRBGY7UIW';
    const STEAM_SECRET = 'XJGTDRUUKZH3X7TQN2QZUGCGXZCC5LXE';
    const ALGORITHM_DEFAULT = 'sha1';
    const ALGORITHM_CUSTOM = 'sha256';
    const DIGITS_DEFAULT = 6;
    const DIGITS_CUSTOM = 7;
    const DIGITS_STEAM = 5;
    const PERIOD_DEFAULT = 30;
    const PERIOD_CUSTOM = 40;
    const COUNTER_DEFAULT = 0;
    const COUNTER_CUSTOM = 5;
    const IMAGE = 'https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png';
    const ICON = 'test.png';
    const TOTP_FULL_CUSTOM_URI = 'otpauth://totp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&period='.self::PERIOD_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM.'&image='.self::IMAGE;
    const HOTP_FULL_CUSTOM_URI = 'otpauth://hotp/'.self::SERVICE.':'.self::ACCOUNT.'?secret='.self::SECRET.'&issuer='.self::SERVICE.'&digits='.self::DIGITS_CUSTOM.'&counter='.self::COUNTER_CUSTOM.'&algorithm='.self::ALGORITHM_CUSTOM.'&image='.self::IMAGE;
    const TOTP_SHORT_URI = 'otpauth://totp/'.self::ACCOUNT.'?secret='.self::SECRET;
    const HOTP_SHORT_URI = 'otpauth://hotp/'.self::ACCOUNT.'?secret='.self::SECRET;
    const TOTP_URI_WITH_UNREACHABLE_IMAGE = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&image=https%3A%2F%2Fen.opensuse.org%2Fimage.png';
    const INVALID_OTPAUTH_URI = 'otpauth://Xotp/'.self::ACCOUNT.'?secret='.self::SECRET;
    const STEAM_TOTP_URI = 'otpauth://totp/'.self::STEAM.':'.self::ACCOUNT.'?secret='.self::STEAM_SECRET.'&issuer='.self::STEAM.'&digits='.self::DIGITS_STEAM.'&period=30&algorithm='.self::ALGORITHM_DEFAULT;

    const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => self::ICON,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => self::PERIOD_CUSTOM,
        'counter'   => null,
    ];
    const ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
    ];
    const ARRAY_OF_PARAMETERS_FOR_UNSUPPORTED_OTP_TYPE = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'Xotp',
        'secret'    => self::SECRET,
    ];
    const ARRAY_OF_INVALID_PARAMETERS_FOR_TOTP = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'totp',
        'secret'    => 0,
    ];
    const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => self::ICON,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => null,
        'counter'   => self::COUNTER_CUSTOM,
    ];
    const ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP = [
        'account'   => self::ACCOUNT,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
    ];
    const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_STEAM_TOTP = [
        'service'   => self::STEAM,
        'account'   => self::ACCOUNT,
        'otp_type'  => 'steamtotp',
        'secret'    => self::STEAM_SECRET,
        'digits'    => self::DIGITS_STEAM,
        'algorithm' => self::ALGORITHM_DEFAULT,
        'period'    => self::PERIOD_DEFAULT,
        'counter'   => null,
    ];

    const GOOGLE_AUTH_MIGRATION_URI = 'otpauth-migration://offline?data=CiQKCgcNEp61iE2P0RYSB2FjY291bnQaB3NlcnZpY2UgASgBMAIKLAoKBw0SnrWITY/RFhILYWNjb3VudF9iaXMaC3NlcnZpY2VfYmlzIAEoATACEAEYASAA';
    const INVALID_GOOGLE_AUTH_MIGRATION_URI = 'otpauthmigration://offline?data=CiQKCgcNEp61iE2P0RYSB2FjY291bnQaB3NlcnZpY2UgASgBMAIKLAoKBw0SnrWITY/RFhILYWNjb3VudF9iaXMaC3NlcnZpY2VfYmlzIAEoATACEAEYASAA';
    const GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA = 'otpauth-migration://offline?data=CiQKCgcNEp61iE2P0RYSB2FjY291bnQaB3NlcnZpY';
}
