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

    const IMAGE = 'https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png';

    const ICON_PNG = 'test.png';

    const ICON_PNG_DATA = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAAC0lEQVQImWP4DwQACfsD/eNV8pwAAAAASUVORK5CYII=';

    const ICON_JPEG = 'test.jpg';

    const ICON_JPEG_DATA = '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAFA3PEY8MlBGQUZaVVBfeMiCeG5uePWvuZHI////////////////////////////////////////////////////2wBDAVVaWnhpeOuCguv/////////////////////////////////////////////////////////////////////////wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwC7RRRQB//Z';

    const ICON_WEBP = 'test.webp';

    const ICON_WEBP_DATA = 'UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAB0CWJaQAA3AA/u9gAAA=';

    const ICON_BMP = 'test.bmp';

    const ICON_BMP_DATA = 'Qk2OAAAAAAAAAIoAAAB8AAAAAQAAAAEAAAABACAAAwAAACAAAAATCwAAEwsAAAAAAAAAAAAAAAD/AAD/AAD/AAAAAAAA/0JHUnMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMJXy/w==';

    const ICON_SVG = 'test.svg';

    const ICON_SVG_DATA = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024"><circle cx="512" cy="512" r="512" style="fill:#000e9c"/><path d="m700.2 466.5 61.2-106.3c23.6 41.6 37.2 89.8 37.2 141.1 0 68.8-24.3 131.9-64.7 181.4H575.8l48.7-84.6h-64.4l75.8-131.7 64.3.1zm-55.4-125.2L448.3 682.5l.1.2H290.1c-40.5-49.5-64.7-112.6-64.7-181.4 0-51.4 13.6-99.6 37.3-141.3l102.5 178.2 113.3-197h166.3z" style="fill:#fff"/></svg>';

    const ICON_SVG_DATA_ENCODED = 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDI0IDEwMjQiPg0KICAgPGNpcmNsZSBjeD0iNTEyIiBjeT0iNTEyIiByPSI1MTIiIHN0eWxlPSJmaWxsOiMwMDBlOWMiLz4NCiAgIDxwYXRoIGQ9Im03MDAuMiA0NjYuNSA2MS4yLTEwNi4zYzIzLjYgNDEuNiAzNy4yIDg5LjggMzcuMiAxNDEuMSAwIDY4LjgtMjQuMyAxMzEuOS02NC43IDE4MS40SDU3NS44bDQ4LjctODQuNmgtNjQuNGw3NS44LTEzMS43IDY0LjMuMXptLTU1LjQtMTI1LjJMNDQ4LjMgNjgyLjVsLjEuMkgyOTAuMWMtNDAuNS00OS41LTY0LjctMTEyLjYtNjQuNy0xODEuNCAwLTUxLjQgMTMuNi05OS42IDM3LjMtMTQxLjNsMTAyLjUgMTc4LjIgMTEzLjMtMTk3aDE2Ni4zeiIgc3R5bGU9ImZpbGw6I2ZmZiIvPg0KPC9zdmc+DQo=';

    const TOTP_FULL_CUSTOM_URI_NO_IMG = 'otpauth://totp/' . self::SERVICE . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::SERVICE . '&digits=' . self::DIGITS_CUSTOM . '&period=' . self::PERIOD_CUSTOM . '&algorithm=' . self::ALGORITHM_CUSTOM;

    const TOTP_FULL_CUSTOM_URI = self::TOTP_FULL_CUSTOM_URI_NO_IMG . '&image=' . self::IMAGE;

    const HOTP_FULL_CUSTOM_URI_NO_IMG = 'otpauth://hotp/' . self::SERVICE . ':' . self::ACCOUNT . '?secret=' . self::SECRET . '&issuer=' . self::SERVICE . '&digits=' . self::DIGITS_CUSTOM . '&counter=' . self::COUNTER_CUSTOM . '&algorithm=' . self::ALGORITHM_CUSTOM;

    const HOTP_FULL_CUSTOM_URI = self::HOTP_FULL_CUSTOM_URI_NO_IMG . '&image=' . self::IMAGE;

    const TOTP_SHORT_URI = 'otpauth://totp/' . self::ACCOUNT . '?secret=' . self::SECRET;

    const HOTP_SHORT_URI = 'otpauth://hotp/' . self::ACCOUNT . '?secret=' . self::SECRET;

    const TOTP_URI_WITH_UNREACHABLE_IMAGE = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&image=https%3A%2F%2Fen.opensuse.org%2Fimage.png';

    const INVALID_OTPAUTH_URI = 'otpauth://Xotp/' . self::ACCOUNT . '?secret=' . self::SECRET;

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
