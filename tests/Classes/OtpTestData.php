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

    const AEGIS_JSON_MIGRATION_PAYLOAD = '
    {
        "version": 1,
        "header": {
            "slots": null,
            "params": null
        },
        "db": {
            "version": 2,
            "entries": [
                {
                    "type": "totp",
                    "uuid": "5be1b189-260d-4fe1-930a-a78cb669dd86",
                    "name": "'.self::ACCOUNT.'_totp",
                    "issuer": "'.self::SERVICE.'_totp",
                    "note": "",
                    "icon": null,
                    "info": {
                        "secret": "'.self::SECRET.'",
                        "algo": "'.self::ALGORITHM_DEFAULT.'",
                        "digits": '.self::DIGITS_DEFAULT.',
                        "period": '.self::PERIOD_DEFAULT.'
                    }
                },
                {
                    "type": "totp",
                    "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                    "name": "'.self::ACCOUNT.'_totp_custom",
                    "issuer": "'.self::SERVICE.'_totp_custom",
                    "note": "",
                    "icon": null,
                    "info": {
                        "secret": "'.self::SECRET.'",
                        "algo": "'.self::ALGORITHM_CUSTOM.'",
                        "digits": '.self::DIGITS_CUSTOM.',
                        "period": '.self::PERIOD_CUSTOM.'
                    }
                },
                {
                    "type": "hotp",
                    "uuid": "90a2af2e-2857-4515-bb18-52c4fa823f6f",
                    "name": "'.self::ACCOUNT.'_hotp",
                    "issuer": "'.self::SERVICE.'_hotp",
                    "note": "",
                    "icon": null,
                    "info": {
                        "secret": "'.self::SECRET.'",
                        "algo": "'.self::ALGORITHM_DEFAULT.'",
                        "digits": '.self::DIGITS_DEFAULT.',
                        "counter": '.self::COUNTER_DEFAULT.'
                    }
                },
                {
                    "type": "hotp",
                    "uuid": "e1b3f683-d5fe-4126-b616-8c8abd8ad97c",
                    "name": "'.self::ACCOUNT.'_hotp_custom",
                    "issuer": "'.self::SERVICE.'_hotp_custom",
                    "note": "",
                    "icon": null,
                    "info": {
                        "secret": "'.self::SECRET.'",
                        "algo": "'.self::ALGORITHM_CUSTOM.'",
                        "digits": '.self::DIGITS_CUSTOM.',
                        "counter": '.self::COUNTER_CUSTOM.'
                    }
                },
                {
                    "type": "steamtotp",
                    "uuid": "9fb06143-421d-46e1-a7e9-4aafe44b0e72",
                    "name": "'.self::ACCOUNT.'_steam",
                    "issuer": "'.self::STEAM.'",
                    "note": "",
                    "icon": "null",
                    "info": {
                        "secret": "'.self::STEAM_SECRET.'",
                        "algo": "'.self::ALGORITHM_DEFAULT.'",
                        "digits": '.self::DIGITS_STEAM.',
                        "period": '.self::PERIOD_DEFAULT.'
                    }
                }
            ]
        }
    }';

    const INVALID_AEGIS_JSON_MIGRATION_PAYLOAD = '
    {
        "version": 1,
        "header": {
            "slots": null,
            "params": null
        },
        "db": {
            "version": 2,
            "thisIsNotTheCorrectKeyName": [
                {
                    "type": "totp",
                    "uuid": "5be1b189-260d-4fe1-930a-a78cb669dd86",
                    "name": "'.self::ACCOUNT.'",
                    "issuer": "'.self::SERVICE.'",
                    "note": "",
                    "icon": null,
                    "info": {
                        "secret": "'.self::SECRET.'",
                        "algo": "'.self::ALGORITHM_DEFAULT.'",
                        "digits": '.self::DIGITS_DEFAULT.',
                        "period": '.self::PERIOD_DEFAULT.'
                    }
                }
            ]
        }
    }';

    const ENCRYPTED_AEGIS_JSON_MIGRATION_PAYLOAD = '
    {
        "version": 1,
        "header": {
            "slots": [
                {
                    "type": 1,
                    "uuid": "1f447956-c71c-4be4-8192-97197dc67df7",
                    "key": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae",
                    "key_params": {
                        "nonce": "77a8ff6d84265efd2a3ed9b7",
                        "tag": "cc13fb4a5baz3fd27bc97f5eacaa00d0"
                    },
                    "n": 32768,
                    "r": 8,
                    "p": 1,
                    "salt": "1c245b2696b948dt040c30c538aeb6f9620b054d9ff182f33dd4b285b67bed51",
                    "repaired": true
                }
            ],
            "params": {
                "nonce": "f31675d9966d2z588bd07788",
                "tag": "ad5729fa135dc6d6sw87e0c955932661"
            }
        },
        "db": "1rX0ajzsxNbhN2hvnNCMBNooLlzqwz\/LMT3bNEIJjPH+zIvIbA6GVVPHLpna+yvjxLPKVkt1OQig=="
    }';

}
