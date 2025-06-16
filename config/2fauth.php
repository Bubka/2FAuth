<?php

use App\Helpers\Helpers;
use Illuminate\Support\Arr;

$preferences = [
    'showOtpAsDot'           => envUnlessEmpty('USERPREF_DEFAULT__SHOW_OTP_AS_DOT', false),
    'showNextOtp'            => envUnlessEmpty('USERPREF_DEFAULT__SHOW_NEXT_OTP', true),
    'revealDottedOTP'        => envUnlessEmpty('USERPREF_DEFAULT__REVEAL_DOTTED_OTP', false),
    'closeOtpOnCopy'         => envUnlessEmpty('USERPREF_DEFAULT__CLOSE_OTP_ON_COPY', false),
    'copyOtpOnDisplay'       => envUnlessEmpty('USERPREF_DEFAULT__COPY_OTP_ON_DISPLAY', false),
    'clearSearchOnCopy'      => envUnlessEmpty('USERPREF_DEFAULT__CLEAR_SEARCH_ON_COPY', false),
    'useBasicQrcodeReader'   => envUnlessEmpty('USERPREF_DEFAULT__USE_BASIC_QRCODE_READER', false),
    'displayMode'            => envUnlessEmpty('USERPREF_DEFAULT__DISPLAY_MODE', 'list'),
    'showAccountsIcons'      => envUnlessEmpty('USERPREF_DEFAULT__SHOW_ACCOUNTS_ICONS', true),
    'iconCollection'         => envUnlessEmpty('USERPREF_DEFAULT__ICON_COLLECTION', 'selfh'),
    'iconVariant'            => envUnlessEmpty('USERPREF_DEFAULT__ICON_VARIANT', 'regular'),
    'iconVariantStrictFetch' => envUnlessEmpty('USERPREF_DEFAULT__ICON_VARIANT_STRICT_FETCH', false),
    'kickUserAfter'          => envUnlessEmpty('USERPREF_DEFAULT__KICK_USER_AFTER', 15),
    'activeGroup'            => 0,
    'rememberActiveGroup'    => envUnlessEmpty('USERPREF_DEFAULT__REMEMBER_ACTIVE_GROUP', true),
    'viewDefaultGroupOnCopy' => envUnlessEmpty('USERPREF_DEFAULT__VIEW_DEFAULT_GROUP_ON_COPY', false),
    'defaultGroup'           => 0,
    'defaultCaptureMode'     => envUnlessEmpty('USERPREF_DEFAULT__DEFAULT_CAPTURE_MODE', 'livescan'),
    'useDirectCapture'       => envUnlessEmpty('USERPREF_DEFAULT__USE_DIRECT_CAPTURE', false),
    'useWebauthnOnly'        => envUnlessEmpty('USERPREF_DEFAULT__USE_WEBAUTHN_ONLY', false),
    'getOfficialIcons'       => envUnlessEmpty('USERPREF_DEFAULT__GET_OFFICIAL_ICONS', true),
    'theme'                  => envUnlessEmpty('USERPREF_DEFAULT__THEME', 'system'),
    'formatPassword'         => envUnlessEmpty('USERPREF_DEFAULT__FORMAT_PASSWORD', true),
    'formatPasswordBy'       => envUnlessEmpty('USERPREF_DEFAULT__FORMAT_PASSWORD_BY', 0.5),
    'lang'                   => envUnlessEmpty('USERPREF_DEFAULT__LANG', 'browser'),
    'getOtpOnRequest'        => envUnlessEmpty('USERPREF_DEFAULT__GET_OTP_ON_REQUEST', true),
    'notifyOnNewAuthDevice'  => envUnlessEmpty('USERPREF_DEFAULT__NOTIFY_ON_NEW_AUTH_DEVICE', false),
    'notifyOnFailedLogin'    => envUnlessEmpty('USERPREF_DEFAULT__NOTIFY_ON_FAILED_LOGIN', false),
    'timezone'               => envUnlessEmpty('USERPREF_DEFAULT__TIMEZONE', 'UTC'),
    'sortCaseSensitive'      => envUnlessEmpty('USERPREF_DEFAULT__SORT_CASE_SENSITIVE', false),
    'autoCloseTimeout'       => envUnlessEmpty('USERPREF_DEFAULT__AUTO_CLOSE_TIMEOUT', 2),
    'AutoSaveQrcodedAccount' => envUnlessEmpty('USERPREF_DEFAULT__AUTO_SAVE_QRCODED_ACCOUNT', false),
    'showEmailInFooter'      => envUnlessEmpty('USERPREF_DEFAULT__SHOW_EMAIL_IN_FOOTER', true),
];

$nonLockablePreferences = [
    'activeGroup',
    'defaultGroup',
    'useWebauthnOnly',
];

return [

    /*
    |--------------------------------------------------------------------------
    | Application infos
    |--------------------------------------------------------------------------
    |
    */

    'version' => '5.6.0',
    'repository' => 'https://github.com/Bubka/2FAuth',
    'latestReleaseUrl' => 'https://api.github.com/repos/Bubka/2FAuth/releases/latest',
    'installDocUrl' => 'https://docs.2fauth.app/getting-started/installation/self-hosted-server/',
    'ssoDocUrl' => 'https://docs.2fauth.app/security/authentication/sso/',
    'exportSchemaUrl' => 'https://docs.2fauth.app/usage/migration/#export-schema',

    /*
    |--------------------------------------------------------------------------
    | 2FAuth config
    |--------------------------------------------------------------------------
    |
    */

    'config' => [
        'isDemoApp' => env('IS_DEMO_APP', false),
        'isTestingApp' => env('IS_TESTING_APP', false),
        'trustedProxies' => env('TRUSTED_PROXIES', null),
        'outgoingProxy' => env('PROXY_FOR_OUTGOING_REQUESTS', ''),
        'proxyLogoutUrl' => env('PROXY_LOGOUT_URL', null),
        'appSubdirectory' => env('APP_SUBDIRECTORY', ''),
        'authLogRetentionTime' => envUnlessEmpty('AUTHENTICATION_LOG_RETENTION', 365),
        'contentSecurityPolicy' => envUnlessEmpty('CONTENT_SECURITY_POLICY', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy headers
    |--------------------------------------------------------------------------
    |
    */
    
    'proxy_headers' => [
        'forIp' => env('PROXY_HEADER_FOR_IP', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FAuth API config
    |--------------------------------------------------------------------------
    |
    */

    'api' => [
        'throttle' => env('THROTTLE_API', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | 2FAuth available translations
    |--------------------------------------------------------------------------
    |
    */

    'locales' => [
        'bg',
        'zh-CN',
        'da',
        'nl',
        'en',
        'fr',
        'de',
        'hi',
        'it',
        'ja',
        'ko',
        'pt-BR',
        'ru',
        'es-ES',
        'tr',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default values for app (global) settings
    | These settings can be overloaded and persisted using the SettingService
    |--------------------------------------------------------------------------
    |
    */

    'settings' => [
        'useEncryption' => false,
        'checkForUpdate' => true,
        'lastRadarScan' => 0,
        'latestRelease' => false,
        'disableRegistration' => false,
        'enableSso' => true,
        'useSsoOnly' => false,
        'allowPatWhileSsoOnly' => false,
        'restrictRegistration' => false,
        'restrictList' => '',
        'restrictRule' => '',
        'keepSsoRegistrationEnabled' => false,
        'storeIconsInDatabase' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default values for user preferences
    | These settings can be overloaded and persisted by each user
    |--------------------------------------------------------------------------
    |
    */

    'preferences' => $preferences,
    

    /*
    |--------------------------------------------------------------------------
    | List of user preferences locked against user customization
    | These settings cannot be overloaded and persisted by each user
    |--------------------------------------------------------------------------
    |
    */

    'lockedPreferences' => Helpers::lockedPreferences(Arr::except($preferences, $nonLockablePreferences)),

];
