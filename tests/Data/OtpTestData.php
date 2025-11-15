<?php

namespace Tests\Data;

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

    const EXTERNAL_IMAGE_URL_DECODED = 'https://en.opensuse.org/images/4/44/Button-filled-colour.png';

    const EXTERNAL_IMAGE_URL_ENCODED = 'https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png';

    const EXTERNAL_INFECTED_IMAGE_URL_DECODED = 'https://image.com/infected.svg';

    const EXTERNAL_INFECTED_IMAGE_URL_ENCODED = 'https%3A%2F%2Fimage.com%2Finfected.svg';

    const ICON_NAME = 'test';

    const ICON_PNG = self::ICON_NAME . '.png';

    const ICON_PNG_DATA = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAC0lEQVQImWP4DwQACfsD/eNV8pwAAAAASUVORK5CYII=';

    const ICON_JPEG = self::ICON_NAME . '.jpg';

    const ICON_JPEG_DATA = '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAFA3PEY8MlBGQUZaVVBfeMiCeG5uePWvuZHI////////////////////////////////////////////////////2wBDAVVaWnhpeOuCguv/////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwC7RRRQB//Z';

    const ICON_WEBP = self::ICON_NAME . '.webp';

    const ICON_WEBP_DATA = 'UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAB0CWJaQAA3AA/u9gAAA=';

    const ICON_BMP = self::ICON_NAME . '.bmp';

    const ICON_BMP_DATA = 'Qk2OAAAAAAAAAIoAAAB8AAAAAQAAAAEAAAABACAAAwAAACAAAAATCwAAEwsAAAAAAAAAAAAAAAD/AAD/AAD/AAAAAAAA/0JHUnMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMJXy/w==';

    const ICON_SVG = self::ICON_NAME . '.svg';

    const ICON_SVG_DATA = '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024"><circle cx="512" cy="512" r="512" style="fill:#000e9c"></circle><path d="m700.2 466.5 61.2-106.3c23.6 41.6 37.2 89.8 37.2 141.1 0 68.8-24.3 131.9-64.7 181.4H575.8l48.7-84.6h-64.4l75.8-131.7 64.3.1zm-55.4-125.2L448.3 682.5l.1.2H290.1c-40.5-49.5-64.7-112.6-64.7-181.4 0-51.4 13.6-99.6 37.3-141.3l102.5 178.2 113.3-197h166.3z" style="fill:#fff"></path></svg>';

    const ICON_SVG_DATA_ENCODED = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4gPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDI0IDEwMjQiPjxjaXJjbGUgY3g9IjUxMiIgY3k9IjUxMiIgcj0iNTEyIiBzdHlsZT0iZmlsbDojMDAwZTljIj48L2NpcmNsZT48cGF0aCBkPSJtNzAwLjIgNDY2LjUgNjEuMi0xMDYuM2MyMy42IDQxLjYgMzcuMiA4OS44IDM3LjIgMTQxLjEgMCA2OC44LTI0LjMgMTMxLjktNjQuNyAxODEuNEg1NzUuOGw0OC43LTg0LjZoLTY0LjRsNzUuOC0xMzEuNyA2NC4zLjF6bS01NS40LTEyNS4yTDQ0OC4zIDY4Mi41bC4xLjJIMjkwLjFjLTQwLjUtNDkuNS02NC43LTExMi42LTY0LjctMTgxLjQgMC01MS40IDEzLjYtOTkuNiAzNy4zLTE0MS4zbDEwMi41IDE3OC4yIDExMy4zLTE5N2gxNjYuM3oiIHN0eWxlPSJmaWxsOiNmZmYiPjwvcGF0aD48L3N2Zz4g';

    const ICON_SVG_MALICIOUS_CODE = '<script>alert("XSS");</script>';

    const ICON_SVG_DATA_INFECTED = '<?xml version="1.0" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg width="100" height="100" version="1.1" xmlns="http://www..w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">' . self::ICON_SVG_MALICIOUS_CODE . '</svg>';

    const ICON_GIF = self::ICON_NAME . '.gif';

    const ICON_GIF_DATA = 'R0lGODlhAQACAPcAAAAAAAAAMwAAZgAAmQAAzAAA/wArAAArMwArZgArmQArzAAr/wBVAABVMwBVZgBVmQBVzABV/wCAAACAMwCAZgCAmQCAzACA/wCqAACqMwCqZgCqmQCqzACq/wDVAADVMwDVZgDVmQDVzADV/wD/AAD/MwD/ZgD/mQD/zAD//zMAADMAMzMAZjMAmTMAzDMA/zMrADMrMzMrZjMrmTMrzDMr/zNVADNVMzNVZjNVmTNVzDNV/zOAADOAMzOAZjOAmTOAzDOA/zOqADOqMzOqZjOqmTOqzDOq/zPVADPVMzPVZjPVmTPVzDPV/zP/ADP/MzP/ZjP/mTP/zDP//2YAAGYAM2YAZmYAmWYAzGYA/2YrAGYrM2YrZmYrmWYrzGYr/2ZVAGZVM2ZVZmZVmWZVzGZV/2aAAGaAM2aAZmaAmWaAzGaA/2aqAGaqM2aqZmaqmWaqzGaq/2bVAGbVM2bVZmbVmWbVzGbV/2b/AGb/M2b/Zmb/mWb/zGb//5kAAJkAM5kAZpkAmZkAzJkA/5krAJkrM5krZpkrmZkrzJkr/5lVAJlVM5lVZplVmZlVzJlV/5mAAJmAM5mAZpmAmZmAzJmA/5mqAJmqM5mqZpmqmZmqzJmq/5nVAJnVM5nVZpnVmZnVzJnV/5n/AJn/M5n/Zpn/mZn/zJn//8wAAMwAM8wAZswAmcwAzMwA/8wrAMwrM8wrZswrmcwrzMwr/8xVAMxVM8xVZsxVmcxVzMxV/8yAAMyAM8yAZsyAmcyAzMyA/8yqAMyqM8yqZsyqmcyqzMyq/8zVAMzVM8zVZszVmczVzMzV/8z/AMz/M8z/Zsz/mcz/zMz///8AAP8AM/8AZv8Amf8AzP8A//8rAP8rM/8rZv8rmf8rzP8r//9VAP9VM/9VZv9Vmf9VzP9V//+AAP+AM/+AZv+Amf+AzP+A//+qAP+qM/+qZv+qmf+qzP+q///VAP/VM//VZv/Vmf/VzP/V////AP//M///Zv//mf//zP///wAAAAAAAAAAAAAAACH5BAEAAPwALAAAAAABAAIAAAgFAPftCwgAOw==';

    const TOTP_FULL_CUSTOM_URI_NO_IMG = 'otpauth://totp/' . self::SERVICE . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::SERVICE . '&digits=' . self::DIGITS_CUSTOM . '&period=' . self::PERIOD_CUSTOM . '&algorithm=' . self::ALGORITHM_CUSTOM;

    const MICROSOFT = 'Microsoft';

    const ORGANIZATION = 'MyOrganization';

    const TOTP_MICROSOFT_CORPORATE_URI_MISMATCHING_ISSUER = 'otpauth://totp/' . self::ORGANIZATION . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::MICROSOFT;

    const TOTP_FULL_CUSTOM_URI = self::TOTP_FULL_CUSTOM_URI_NO_IMG . '&image=' . self::EXTERNAL_IMAGE_URL_ENCODED;

    const HOTP_FULL_CUSTOM_URI_NO_IMG = 'otpauth://hotp/' . self::SERVICE . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::SERVICE . '&digits=' . self::DIGITS_CUSTOM . '&counter=' . self::COUNTER_CUSTOM . '&algorithm=' . self::ALGORITHM_CUSTOM;

    const HOTP_FULL_CUSTOM_URI = self::HOTP_FULL_CUSTOM_URI_NO_IMG . '&image=' . self::EXTERNAL_IMAGE_URL_ENCODED;

    const TOTP_SHORT_URI = 'otpauth://totp/' . self::ACCOUNT . '?secret=' . self::SECRET;

    const HOTP_SHORT_URI = 'otpauth://hotp/' . self::ACCOUNT . '?secret=' . self::SECRET;

    const UNREACHABLE_IMAGE_URL = 'https%3A%2F%2Fwww.example.com%2Fimage.png';

    const UNREACHABLE_IMAGE_URL_DECODED = 'https://www.example.com/image.png';

    const TOTP_URI_WITH_UNREACHABLE_IMAGE = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&image=' . self::UNREACHABLE_IMAGE_URL;

    const TOTP_URI_WITH_INFECTED_SVG_IMAGE = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&image=' . self::EXTERNAL_INFECTED_IMAGE_URL_ENCODED;

    const INVALID_OTPAUTH_URI = 'otpauth://Xotp/' . self::ACCOUNT . '?secret=' . self::SECRET;

    const INVALID_OTPAUTH_URI_MISMATCHING_ISSUER = 'otpauth://totp/' . self::MICROSOFT . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::SERVICE;

    const STEAM_TOTP_URI = 'otpauth://totp/' . self::STEAM . ':' . self::ACCOUNT . '?secret=' . self::STEAM_SECRET . '&issuer=' . self::STEAM . '&digits=' . self::DIGITS_STEAM . '&period=30&algorithm=' . self::ALGORITHM_DEFAULT;

    const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => self::ICON_PNG,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => self::PERIOD_CUSTOM,
        'counter'   => null,
    ];

    const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP_NO_ICON = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => null,
        'otp_type'  => 'totp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => self::PERIOD_CUSTOM,
        'counter'   => null,
    ];

    const ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_TOTP = [
        'account'  => self::ACCOUNT,
        'otp_type' => 'totp',
        'secret'   => self::SECRET,
    ];

    const ARRAY_OF_PARAMETERS_FOR_UNSUPPORTED_OTP_TYPE = [
        'account'  => self::ACCOUNT,
        'otp_type' => 'Xotp',
        'secret'   => self::SECRET,
    ];

    const ARRAY_OF_INVALID_PARAMETERS_FOR_TOTP = [
        'account'  => self::ACCOUNT,
        'otp_type' => 'totp',
        'secret'   => 0,
    ];

    const ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_HOTP = [
        'service'   => self::SERVICE,
        'account'   => self::ACCOUNT,
        'icon'      => self::ICON_PNG,
        'otp_type'  => 'hotp',
        'secret'    => self::SECRET,
        'digits'    => self::DIGITS_CUSTOM,
        'algorithm' => self::ALGORITHM_CUSTOM,
        'period'    => null,
        'counter'   => self::COUNTER_CUSTOM,
    ];

    const ARRAY_OF_MINIMUM_VALID_PARAMETERS_FOR_HOTP = [
        'account'  => self::ACCOUNT,
        'otp_type' => 'hotp',
        'secret'   => self::SECRET,
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
}
