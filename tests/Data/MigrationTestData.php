<?php

namespace Tests\Data;

class MigrationTestData
{
    const GOOGLE_AUTH_MIGRATION_URI = 'otpauth-migration://offline?data=CiQKCgcNEp61iE2P0RYSB2FjY291bnQaB3NlcnZpY2UgASgBMAIKLAoKBw0SnrWITY/RFhILYWNjb3VudF9iaXMaC3NlcnZpY2VfYmlzIAEoATACEAEYASAA';

    const INVALID_GOOGLE_AUTH_MIGRATION_URI = 'otpauthmigration://offline?data=CiQKCgcNEp61iE2P0RYSB2FjY291bnQaB3NlcnZpY2UgASgBMAIKLAoKBw0SnrWITY/RFhILYWNjb3VudF9iaXMaC3NlcnZpY2VfYmlzIAEoATACEAEYASAA';

    const GOOGLE_AUTH_MIGRATION_URI_WITH_INVALID_DATA = 'otpauth-migration://offline?data=CiQKCgcNEp61iE2P0RYSB2FjY291bnQaB3NlcnZpY';

    const VALID_PLAIN_TEXT_PAYLOAD = OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . PHP_EOL .
        OtpTestData::HOTP_FULL_CUSTOM_URI_NO_IMG . PHP_EOL .
        PHP_EOL .
        OtpTestData::STEAM_TOTP_URI . PHP_EOL .
        PHP_EOL;

    const VALID_PLAIN_TEXT_PAYLOAD_WITH_INTRUDER = OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . PHP_EOL .
        OtpTestData::HOTP_FULL_CUSTOM_URI_NO_IMG . PHP_EOL .
        'This is an intruder line' . PHP_EOL .
        OtpTestData::STEAM_TOTP_URI;

    const INVALID_PLAIN_TEXT_NO_URI = '
        
        XJGTDRUUKZH3X7TQN2QZUGCGXZCC5LXE
    ';

    const INVALID_PLAIN_TEXT_ONLY_EMPTY_LINES = '
        
        
    ';

    const PLAIN_TEXT_PAYLOAD_WITH_INVALID_URI = OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . PHP_EOL .
        'otpauth://totp/';

    const VALID_AEGIS_JSON_MIGRATION_PAYLOAD = '
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
                        "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": null,
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "period": ' . OtpTestData::PERIOD_CUSTOM . '
                        }
                    },
                    {
                        "type": "hotp",
                        "uuid": "e1b3f683-d5fe-4126-b616-8c8abd8ad97c",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": null,
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "counter": ' . OtpTestData::COUNTER_CUSTOM . '
                        }
                    },
                    {
                        "type": "steamtotp",
                        "uuid": "9fb06143-421d-46e1-a7e9-4aafe44b0e72",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::STEAM . '",
                        "note": "",
                        "icon": "null",
                        "info": {
                            "secret": "' . OtpTestData::STEAM_SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_DEFAULT . '",
                            "digits": ' . OtpTestData::DIGITS_STEAM . ',
                            "period": ' . OtpTestData::PERIOD_DEFAULT . '
                        }
                    }
                ]
            }
        }';

    const VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_SVG_ICON = '
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
                        "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": "' . OtpTestData::ICON_SVG_DATA_ENCODED . '",
                        "icon_mime": "image\/svg+xml",
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "period": ' . OtpTestData::PERIOD_CUSTOM . '
                        }
                    }
                ]
            }
        }';

    const VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_PNG_ICON = '
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
                        "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": "iVBORw0KGgoAAAANSUhEUgAAA+gAAAPoAQMAAAB3bUanAAAABlBMVEUAAAD///+l2Z",
                        "icon_mime": "image\/png",
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "period": ' . OtpTestData::PERIOD_CUSTOM . '
                        }
                    }
                ]
            }
        }';

    const VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_JPG_ICON = '
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
                        "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": "/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAFA3PEY8MlBGQUZaVVBfeMiCeG5uePWvuZHI////////////////////////////////////////////////////2wBDAVVaWnhpeOuCguv/////////////////////////////////////////////////////////////////////////wAARCAAyADIDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwCIeUkEbNFvLZ/iI6GpYlTzYHRNu7dkZz0oWJ/KVHt923ODvx1p6I/mxfudiJn+LPWgBjWyJEwf5duMSdc/h+lP+0JJL8i7nX7nOM56/wCTUVsGkVU3bo+d64xj0569fSpIY1haENH+8bdznp/TpQA+4RXnhVhkHd/Kq8JgllCeRjPfealUyusEoXeRuzyB7UJGyMGW1wR/00oAoUVP9kn/ALn6j/GigBqOsM4ZTvA/DPFWI5E80yEbtv3penX2/T9aaZIgshaTzi+OMFelSCNZp2cx7426NnGMD069aAG5SL5JYPLR+p356U1IGkswfvH+AdMc8/5NAMQnjNsu485GSO3vU9yGKsm7JfGxcenXn/GgCAW6qse5cyHPyZ+9+PQYHNSFGZpLdRiMY5/u9/qcmorh3jlX99udc/w4xmrbmUsUVdo/v5B/T9KAKP2Sf+5+o/xoo+yT/wBz9R/jRQBbSeV1DLBkH/bFRLEnmqj2+3dnB356UwxPLbQ7Fzjdnn3qWNGRrZWGCN1AEcSRBXPnbo+N42kfT36+lSQuyQQtnEY3b/z49+vpStNmJJ/KzjP8XTt+OaJNjWwjh53/AHR64PPWgBlvAjxRkx7t2dzbsYp1sjSwKrDEYz/wLn8xg/nUUccCZMp3I33G5GfXp/WiO3drYsjZ39Vx1wfWgB/+m/520VN5s/8Az7/+PiigDMqez/4+U/H+RoooAm07/lp+H9aIP+XX/gdFFABB/wAuv/A6mi/4+Z/+A/yoooAzKKKKAP/Z",
                        "icon_mime": "image\/jpeg",
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "period": ' . OtpTestData::PERIOD_CUSTOM . '
                        }
                    }
                ]
            }
        }';

    const VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_ICON = '
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
                        "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": "iVBORw0KGgoAAAANSUhEUgAAA+gAAAPoAQMAAAB3bUanAAAABlBMVEUAAAD///+l2Z",
                        "icon_mime": "image\/gif",
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "period": ' . OtpTestData::PERIOD_CUSTOM . '
                        }
                    }
                ]
            }
        }';

    const VALID_2FAS_MIGRATION_PAYLOAD = '
        {
            "groups":
            [{
                "id": "C2F69014-C4C7-4EEC-9225-D24E750F77FD",
                "name": "Test",
                "isExpanded": true
            }],
            "schemaVersion": 2,
            "appOrigin": "ios",
            "appVersionCode": 32001,
            "services":
            [{
                "secret": "' . OtpTestData::SECRET . '",
                "badge": {"color": "Default"},
                "order": {"position": 1},
                "otp":
                {
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "counter": 0,
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "tokenType": "TOTP"
                },
                "updatedAt": 1657529430000,
                "name": "' . OtpTestData::SERVICE . '",
                "type": "ManuallyAdded"
            },
            {
                "secret": "' . OtpTestData::SECRET . '",
                "badge": {"color": "Default"},
                "order": {"position": 2},
                "otp":
                {
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "counter": ' . OtpTestData::COUNTER_CUSTOM . ',
                    "period": 30,
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "tokenType": "HOTP"
                },
                "updatedAt": 1657529430000,
                "name": "' . OtpTestData::SERVICE . '",
                "type": "ManuallyAdded",
                "icon":
                {
                    "selected": "Brand",
                    "brand":
                    {
                        "id": "ManuallyAdded"
                    },
                    "label":
                    {
                        "text": "OW",
                        "backgroundColor": "LightBlue"
                    }
                }
            }],
            "appVersionName": "3.20.1"
        }';

    const VALID_2FAS_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE = '
        {
            "groups":
            [{
                "id": "C2F69014-C4C7-4EEC-9225-D24E750F77FD",
                "name": "Test",
                "isExpanded": true
            }],
            "schemaVersion": 2,
            "appOrigin": "ios",
            "appVersionCode": 32001,
            "services":
            [{
                "secret": "' . OtpTestData::SECRET . '",
                "badge": {"color": "Default"},
                "order": {"position": 1},
                "otp":
                {
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "counter": 0,
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "tokenType": "TOTP"
                },
                "updatedAt": 1657529430000,
                "name": "' . OtpTestData::SERVICE . '",
                "type": "ManuallyAdded"
            },
            {
                "secret": "' . OtpTestData::SECRET . '",
                "badge": {"color": "Default"},
                "order": {"position": 2},
                "otp":
                {
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "counter": ' . OtpTestData::COUNTER_CUSTOM . ',
                    "period": 30,
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "tokenType": "XOTP"
                },
                "updatedAt": 1657529430000,
                "name": "' . OtpTestData::SERVICE . '",
                "type": "ManuallyAdded"
            }],
            "appVersionName": "3.20.1"
        }';

    const INVALID_2FAS_MIGRATION_PAYLOAD = '
        {
            "groups":
            [{
                "id": "C2F69014-C4C7-4EEC-9225-D24E750F77FD",
                "name": "Test",
                "isExpanded": true
            }],
            "schemaVersion": 2,
            "appOrigin": "ios",
            "appVersionCode": 32001,
            "service__BAD__NAME___s":
            [{
                "secret": "' . OtpTestData::SECRET . '",
                "badge": {"color": "Default"},
                "order": {"position": 1},
                "otp":
                {
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "counter": 0,
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "tokenType": "TOTP"
                },
                "updatedAt": 1657529430000,
                "name": "' . OtpTestData::SERVICE . '",
                "type": "ManuallyAdded"
            }],
            "appVersionName": "3.20.1"
        }';

    const ENCRYPTED_2FAS_MIGRATION_PAYLOAD = '
        {
            "reference": "RPKUw+hful0yPrvxaDjt2IZWMVgesKPMgJQ/U8CzT6I+eyPo9+yO1xX7jdYYS0aD3DGghxXBN6diIjTe5wKhbWXGvBdCF1a528j1/bgY0cn+MCXf0eG6uQ7oE+idBXeFZsXk5oxqZEA5waYsuwBenILCHqzU7mIlzqUXcNyb3gFF+AhkQGlfUBKeVcIXrbuTuwbtA8IwgJfV8klsUFH96EGsiCMQ2v2MCXVaqFR3TlTMfj4iDg9ULktnKisxfm6O3B/XQtsiDVpnhqQzFSqktbh9vKOrO2NgK40kLmgRkS08SpxiC4Yt0CoGyp9bgLYySdQDvKMnbYxVatx6AJpckVuA7ICiQjbHa5mItGITU78=:yrDmOf1HirJ5N7wlYXyUmL0EtNy2t5oNpvQw1rV22nA=:8MwK8sWyL3iVv+6w",
            "schemaVersion": 2,
            "appVersionCode": 32001,
            "services":
            [],
            "groups":
            [
                {
                    "id": "C2F69014-C4C7-4EEC-9225-D24E750F77FD",
                    "name": "Test",
                    "isExpanded": true
                }
            ],
            "appOrigin": "ios",
            "servicesEncrypted": "xlH6lrz/oW/TJO2WExdt33bI4CvCjE/W2wlcl+8VVbRj/BHrYujY7AexaenaBhoJUB0xeOMDL1q2swvtVYQZLAMFoBcg4XMEVfk5R6R0SjwJd7/cvcv89nKNDX/eHtIHKoaDkgFxk4Po9YN1OtG0M/jXH9DdERu+LAJHboLK8VpnehOJTbs/s0vahrw0q4jZiHGCfV7vXrylsuEjXBVv90/dT0mZE5iiPIteWCJyxl+04cuTLMl6kMUXem0OGTsqOLmmSMVz8sCrf6HRzqH8KFj1sR6h+MGVdsVij8f99O4ZB+tCCy0A3BY8Yn68J8aBAOUU4nzETAl0Zhc489E9X9eibMLkaBt452gfwXU/9muIeA2WM8so3sM6SPQVls8xYu653z7bh7WCCIjrJmZ10DVydK9Krjoe6nd+DQjuNy/vcNFeCe6/CljF3KWfWyNRdcwaUH+yKZE/TFw3P+ZuvudnfHRBE6OsE3c/XmxnI66mSy+SePhmIoe9x0rxC5T5LM5Fw+bWBLR6mLXo1ouxxrljeboqJ4THR6a2x8hhhP8wvK30v/PDPyLTtBdLv+HilQibXF9YqVH562BhlmhLAe5dv67srP6JwK10tAo5TfhKjewMqTouOiCqT+/mvvqpTV/1f676mzF7s1Rh83ikfqzT/u5OJrFqZdu3tC86KqCLIDx0fQYF/Ha88tXPkVGvPXygH3Adgf7c8spyC9s3yeAAtSSc/qtIpm1Ux5XvaZx3M9cD9ku0KLJqE+rJr0HS2AatS54I/ybdHSbMLaPRdV67jeM1OwCkY5F+FXQpFfcQkpmgCMTvFddnkhUqTR8GgqXcOg2aPcwNQ/EqhIJr+sXqfFS85+eSlroErvUuR9gO/awB2XA4NsZI6bHK9GNebcSTmA16FhiuAFpOqfWcdN3iPeKS/kcUJ8KRlsAO0x9UINnkwJtpSGSA8bdXkOFfnd4rPPDcBGrx7dBtPC+Sc5Y8nvbkLcxn8/CCFgenQByPwGLUp4NuMz8H1/1EwNQ4jF5swoqdnhz6ldBAF+T1trOzQEmpFfG54lur3yEoiYipaZ5WTKJnp6X0GHkgT3Wi0N0im4oGEsqyPEEknm17BamooqRo1JMPA6+cp90udr91DKeLxzRBi8rYaolQjaATbc6rvufcoCuEdOYpGqWfX6mGKD/jmHko4RaoM+7cx6LaH5zRdpBLDzr1aW0TJSAHGgTuTiMJwW73MBaayUf+X+alzlXpcyDlWW+aGpSsYI/TkKTPaIgQFcNWa/+1ayUyg1Tmvuf1J6YgyQJNBVf2LV06I+TziUHPGkwwN7nj/0chqw4sv29I4mpI41C6a/NnoSCa3Vz448DPlk1e80KyMba722gBMTCyU+S1K/UtVE7cPchOETL7X5tl6yoyeh95jbpYX4kJhXHNFZ5XA7d0tFxmYF0mSN5+D63jP2tTqPW7lsz3No9y+VFhQLWTEShrBck1blgNXRFdUCDX5zN4vSYmt44vYGWl3+iDNhYysVh2x2bAGOxCR5EPNPNuUjrxCHRtt8X7Xof7/R0fZF4Qi/F1o6D/ToUpp4XZ/3sjJ1BTouw2Jpx/f2gGDwzFq8uql46eeBgqiQwCSYZTbSSYliVaXh01jJltfqFaMMQF9UGehNdcsAHaLr53I8snMJZLl4IIGwtNgb9cHZYXYM68RtP0UPBntCFA74fWMBVcLLfbpUcGe5fuj4CoBo5gCakYygnsvlqcsmXNR/zaf5xIOC009qVUU0BO+qtgt+TGxAn4K9jgxVOCzR7TPGPvwaOajURwcutj9QXUKuqHz9LwIYZLLzDd2OQxkDoPKfLqKSLP4ZXpKiRP8LkgvZE91ZgxJgCy37STqO1py/VyrHjT3OOmbZ6srwaIOJhZ8/YGh6mecA7n4qJsJUepvD3sbAF0FZJE0xu1kkw=:yrDmOf1HirJ5N7wlYXyUmL0EtNy2t5oNpvQw1rV22nA=:ZhgkRGr/vRf6K1ri",
            "appVersionName": "3.20.1"
        }';

    const VALID_AEGIS_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE = '
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
                        "uuid": "fb2ebd05-9d71-4b2e-9d4e-b7f8d2942bfb",
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": null,
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                            "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                            "period": ' . OtpTestData::PERIOD_CUSTOM . '
                        }
                    },
                    {
                        "type": "xotp",
                        "uuid": "e1b3f683-d5fe-4126-b616-8c8abd8ad97c",
                        "name": "",
                        "issuer": "",
                        "note": "",
                        "icon": null,
                        "info": {
                            "secret": "",
                            "algo": "",
                            "digits": 0,
                            "counter": 0
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
                        "name": "' . OtpTestData::ACCOUNT . '",
                        "issuer": "' . OtpTestData::SERVICE . '",
                        "note": "",
                        "icon": null,
                        "info": {
                            "secret": "' . OtpTestData::SECRET . '",
                            "algo": "' . OtpTestData::ALGORITHM_DEFAULT . '",
                            "digits": ' . OtpTestData::DIGITS_DEFAULT . ',
                            "period": ' . OtpTestData::PERIOD_DEFAULT . '
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

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": null,
                    "icon_mime": null,
                    "icon_file": null,
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                },
                {
                    "otp_type": "hotp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": null,
                    "icon_mime": null,
                    "icon_file": null,
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": null,
                    "counter": ' . OtpTestData::COUNTER_CUSTOM . ',
                    "legacy_uri": "' . OtpTestData::HOTP_FULL_CUSTOM_URI_NO_IMG . '"
                },
                {
                    "otp_type": "steamtotp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::STEAM . '",
                    "icon": null,
                    "icon_mime": null,
                    "icon_file": null,
                    "secret": "' . OtpTestData::STEAM_SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_STEAM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::STEAM_TOTP_URI . '"
                }
            ]
        }';

    const VALID_2FAUTH_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_OTP_TYPE = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": null,
                    "icon_mime": null,
                    "icon_file": null,
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                },
                {
                    "otp_type": "Xotp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": null,
                    "icon_mime": null,
                    "icon_file": null,
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_SVG_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_SVG . '",
                    "icon_mime": "image\/svg+xml",
                    "icon_file": "' . OtpTestData::ICON_SVG_DATA_ENCODED . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_JPG_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_JPEG . '",
                    "icon_mime": "image\/jpeg",
                    "icon_file": "' . OtpTestData::ICON_JPEG_DATA . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_PNG_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_PNG . '",
                    "icon_mime": "image\/png",
                    "icon_file": "' . OtpTestData::ICON_PNG_DATA . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_BMP_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_BMP . '",
                    "icon_mime": "image\/bmp",
                    "icon_file": "' . OtpTestData::ICON_BMP_DATA . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_XBMP_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_BMP . '",
                    "icon_mime": "image\/x-ms-bmp",
                    "icon_file": "' . OtpTestData::ICON_BMP_DATA . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_WEBP_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_WEBP . '",
                    "icon_mime": "image\/webp",
                    "icon_file": "' . OtpTestData::ICON_WEBP_DATA . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const VALID_2FAUTH_JSON_MIGRATION_PAYLOAD_WITH_UNSUPPORTED_ICON = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                {
                    "otp_type": "totp",
                    "account": "' . OtpTestData::ACCOUNT . '",
                    "service": "' . OtpTestData::SERVICE . '",
                    "icon": "' . OtpTestData::ICON_PNG . '",
                    "icon_mime": "image\/gif",
                    "icon_file": "' . OtpTestData::ICON_PNG_DATA . '",
                    "secret": "' . OtpTestData::SECRET . '",
                    "digits": ' . OtpTestData::DIGITS_CUSTOM . ',
                    "algorithm": "' . OtpTestData::ALGORITHM_CUSTOM . '",
                    "period": ' . OtpTestData::PERIOD_CUSTOM . ',
                    "counter": null,
                    "legacy_uri": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                }
            ]
        }';

    const INVALID_2FAUTH_JSON_MIGRATION_PAYLOAD = '
        {
            "app": "2fauth_v3.4.1",
            "schema": 1,
            "datetime": "2022-12-14T14:53:06.173939Z",
            "data":
            [
                ,
            ]
        }';


    const VALID_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD = '
        {
            "encrypted": false,
            "items": [
                {
                    "favorite": false,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "login": {
                        "totp": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '",
                        "username": "' . OtpTestData::ACCOUNT . '"
                    },
                    "name": "' . OtpTestData::SERVICE . '",
                    "type": 1
                },
                {
                    "favorite": false,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "login": {
                        "totp": "' . OtpTestData::HOTP_FULL_CUSTOM_URI_NO_IMG . '",
                        "username": "' . OtpTestData::ACCOUNT . '"
                    },
                    "name": "' . OtpTestData::SERVICE . '",
                    "type": 1
                },
                {
                    "favorite": false,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "login": {
                        "totp": "steam://' . OtpTestData::STEAM_SECRET . '",
                        "username": "' . OtpTestData::ACCOUNT . '"
                    },
                    "name": "' . OtpTestData::STEAM . '",
                    "type": 1
                }
            ]
        }';

    const INVALID_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD = '
        {
            "encrypted": false,
            "thisIsNotTheCorrectKeyName": [
                {
                    "favorite": false,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "login": {
                        "totp": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '",
                        "username": "' . OtpTestData::ACCOUNT . '"
                    },
                    "name": "' . OtpTestData::SERVICE . '",
                    "type": 1
                }
            ]
        }';

    const ENCRYPTED_BITWARDEN_AUTH_JSON_MIGRATION_PAYLOAD = '
        {
            "encrypted": true,
            "items": [
                {
                    "favorite": false,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "login": {
                        "totp": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae",
                        "username": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae"
                    },
                    "name": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae",
                    "type": 1
                }
            ]
        }';

    const VALID_BITWARDEN_JSON_MIGRATION_PAYLOAD = '
        {
            "encrypted": false,
            "folders": [],
            "items": [
                {
                    "passwordHistory": [],
                    "revisionDate": "2025-10-28T15:27:43.012Z",
                    "creationDate": "2024-01-03T12:30:50.043Z",
                    "deletedDate": null,
                    "archivedDate": null,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "organizationId": null,
                    "folderId": null,
                    "type": 1,
                    "reprompt": 0,
                    "name": "' . OtpTestData::SERVICE . '",
                    "notes": null,
                    "favorite": false,
                    "fields": [],
                    "login": {
                    "uris": [
                        {
                        "match": null,
                        "uri": "http://localhost/login"
                        }
                    ],
                    "username": "' . OtpTestData::ACCOUNT . '",
                    "password": "password",
                    "totp": "' . OtpTestData::TOTP_FULL_CUSTOM_URI_NO_IMG . '"
                    },
                    "collectionIds": null
                },
                {
                    "passwordHistory": [],
                    "revisionDate": "2025-10-28T15:27:43.012Z",
                    "creationDate": "2024-01-03T12:30:50.043Z",
                    "deletedDate": null,
                    "archivedDate": null,
                    "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                    "organizationId": null,
                    "folderId": null,
                    "type": 1,
                    "reprompt": 0,
                    "name": "' . OtpTestData::SERVICE . '",
                    "notes": null,
                    "favorite": false,
                    "fields": [],
                    "login": {
                    "uris": [
                        {
                        "match": null,
                        "uri": "http://localhost/login"
                        }
                    ],
                    "username": "' . OtpTestData::ACCOUNT . '",
                    "password": "password",
                    "totp": "' . OtpTestData::HOTP_FULL_CUSTOM_URI_NO_IMG . '"
                    },
                    "collectionIds": null
                }
            ]
        }';

    const ENCRYPTED_BITWARDEN_JSON_MIGRATION_PAYLOAD = '
        {
            "encrypted": true,
            "folders": [],
            "items": [
            {
                "passwordHistory": [],
                "revisionDate": "2025-10-28T15:27:43.012Z",
                "creationDate": "2024-01-03T12:30:50.043Z",
                "deletedDate": null,
                "archivedDate": null,
                "id": "d135d04d-58d0-4f16-83fa-576280caa73d",
                "organizationId": null,
                "folderId": null,
                "type": 1,
                "reprompt": 0,
                "name": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae",
                "notes": null,
                "favorite": false,
                "fields": [],
                "login": {
                "uris": [
                    {
                    "match": null,
                    "uri": "http://localhost/login"
                    }
                ],
                "username": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae",
                "password": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae",
                "totp": "d742967686cae462c5732023a72d59245d8q7c5c93a66aeb2q2a350bb8b6a7ae"
                },
                "collectionIds": null
            }
            ]
        }';
}
