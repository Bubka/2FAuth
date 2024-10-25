<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
   
    // Laravel
    'failed' => 'Brugernavn eller password findes ikke.',
    'password' => 'Forkert password.',
    'throttle' => 'For mange forkerte indtastninger. Prøv igen om :seconds sekunder.',

    // 2FAuth
    'sign_out' => 'Log af',
    'sign_in' => 'Log på',
    'sign_in_using' => 'Log ind med',
    'if_administrator' => 'Administrator?',
    'sign_in_here' => 'Du kan looge ind uden SSO',
    'or_continue_with' => 'Du kan fortsætte med:',
    'password_login_and_webauthn_are_disabled' => 'Password og WebAuthN er deaktiveret.',
    'sign_in_using_sso' => 'Vælg din SSO udbyder for at logge ind:',
    'no_provider' => 'Ingen udbyder',
    'no_sso_provider_or_provider_is_missing' => 'Mangler udbyderen?',
    'see_how_to_enable_sso' => 'Se hvordan man aktiverer en udbyder',
    'sign_in_using_security_device' => 'Log på med en sikker enhed',
    'login_and_password' => 'Login og password',
    'register' => 'Opret',
    'welcome_to_2fauth' => 'Velkommen til 2FAuth',
    'autolock_triggered' => 'Auto-lås udført',
    'autolock_triggered_punchline' => 'Auto-lås udført, du er blevet logget ud',
    'already_authenticated' => 'Allerede autoriseret, log venligst ud først',
    'authentication' => 'Autorisation',
    'maybe_later' => 'Måske senere',
    'user_account_controlled_by_proxy' => 'User account made available by an authentication proxy.<br />Manage the account at proxy level.',
    'auth_handled_by_proxy' => 'Authentication handled by a reverse proxy, below settings are disabled.<br />Manage authentication at proxy level.',
    'sso_only_x_settings_are_disabled' => 'Authentication is restricted to SSO only, :auth_method is disabled',
    'confirm' => [
        'logout' => 'Er du sikker på at du vil logge af?',
        'revoke_device' => 'Are you sure you want to revoke this device?',
        'delete_account' => 'Are you sure you want to delete your account?',
    ],
    'webauthn' => [
        'security_device' => 'a security device',
        'security_devices' => 'Security devices',
        'security_devices_legend' => 'Authentication devices you can use to sign in 2FAuth, like security keys (i.e Yubikey) or smartphones with biometric capabilities (i.e. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'You can enhance the security of your 2FAuth account by enabling WebAuthn authentication.<br /><br />
            WebAuthn allows you to use trusted devices (like Yubikeys or smartphones with biometric capabilities) to sign in quickly and more securely.',
        'use_security_device_to_sign_in' => 'Get ready to authenticate using (one of) your security devices. Plug your key in, remove face mask or gloves, etc.',
        'lost_your_device' => 'Lost your device?',
        'recover_your_account' => 'Gendan din konto',
        'account_recovery' => 'Kontogendannelse',
        'recovery_punchline' => '2FAuth vil sende et link til genoprettelse af adgang via denne e-mail adresse. Tryk på linket i mailen og følg instruktionerne. <br /><br />Åben kun linket på din egen enhed.',
        'send_recovery_link' => 'Send gendannelseslink',
        'account_recovery_email_sent' => 'Mail til kontogendannelse afsendt!',
        'disable_all_security_devices' => 'Disable all security devices',
        'disable_all_security_devices_help' => 'All your security devices will be revoked. Use this option if you have lost one or its security has been compromised.',
        'register_a_new_device' => 'Registrer en ny enhed',
        'register_a_device' => 'Registrer en enhed',
        'device_successfully_registered' => 'Device successfully registered',
        'device_revoked' => 'Device successfully revoked',
        'revoking_a_device_is_permanent' => 'Revoking a device is permanent',
        'recover_account_instructions' => 'To recover your account, 2FAuth resets some Webauthn settings so you will be able to sign in using your email and password.',
        'invalid_recovery_token' => 'Ugyldig recovery token',
        'webauthn_login_disabled' => 'Webauthn er deaktiveret',
        'invalid_reset_token' => 'This reset token is invalid.',
        'rename_device' => 'Rename device',
        'my_device' => 'My device',
        'unknown_device' => 'Unknown device',
        'use_webauthn_only' => [
            'label' => 'Use WebAuthn only',
            'help' => 'Make WebAuthn the only authorized method to log into your 2FAuth account. This is the recommended setup to take advantage of the WebAuthn enhanced security.<br /><br />
                In case of device lost, you will be able to recover your account by resetting this option and signing in using your email and password.<br /><br />
                Attention! The Email & Password form remains available despite this option being enabled, but it will always return an \'Authentication failed\' response.'
        ],
        'need_a_security_device_to_enable_options' => 'Set at least one device to enable the following options',
        'options' => 'Indstillinger',
    ],
    'forms' => [
        'name' => 'Navn',
        'login' => 'Login',
        'webauthn_login' => 'WebAuthn login',
        'sso_login' => 'SSO-login',
        'email' => 'E-mail',
        'password' => 'Adgangskode',
        'reveal_password' => 'Vis adgangskode',
        'hide_password' => 'Skjul adgangskode',
        'confirm_password' => 'Bekræft adgangskode',
        'new_password' => 'Ny adgangskode',
        'confirm_new_password' => 'Bekræft ny adgangskode',
        'dont_have_account_yet' => 'Har du ikke din konto endnu?',
        'already_register' => 'Allerede registreret?',
        'authentication_failed' => 'Godkendelse mislykkedes',
        'forgot_your_password' => 'Glemt din adgangskode?',
        'request_password_reset' => 'Nulstil det',
        'reset_your_password' => 'Nulstil din adgangskode',
        'reset_password' => 'Nulstil adgangskode',
        'disabled_in_demo' => 'Feature disabled in Demo mode',
        'sso_only_form_restricted_to_admin' => 'Regular users must sign in with SSO. Other methods are for administrators only.',
        'new_password' => 'Ny adgangskode',
        'current_password' => [
            'label' => 'Nuværende adgangskode',
            'help' => 'Udfyld din nuværende adgangskode for at bekræfte, at det er dig'
        ],
        'change_password' => 'Skift adgangskode',
        'send_password_reset_link' => 'Send link til nulstilling af adgangskode',
        'password_successfully_reset' => 'Adgangskoden blev ændret',
        'edit_account' => 'Rediger konto',
        'profile_saved' => 'Profile successfully updated!',
        'welcome_to_demo_app_use_those_credentials' => 'Welcome to the 2FAuth demo.<br><br>You can connect using the email address <strong>demo@2fauth.app</strong> and the password <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Welcome to the 2FAuth testing instance.<br><br>Use email address <strong>testing@2fauth.app</strong> and password <strong>password</strong>',
        'register_punchline' => 'Welcome to <b>2FAuth</b>.<br/>You need an account to go further, please register yourself.',
        'reset_punchline' => '2FAuth vil sende dig et link til nulstilling af adgangskode til denne adresse. Klik på linket i den modtagne e-mail for at angive en ny adgangskode.',
        'name_this_device' => 'Name this device',
        'delete_account' => 'Slet konto',
        'delete_your_account' => 'Slet din konto',
        'delete_your_account_and_reset_all_data' => 'Din brugerkonto vil blive slettet såvel som alle dine 2FA-data. Der er ingen vej tilbage.',
        'reset_your_password_to_delete_your_account' => 'If you always used SSO to sign in, sign out then use the reset password feature to get a password so you can fill this form.',
        'deleting_2fauth_account_does_not_impact_provider' => 'Deleting your 2FAuth account has no impact on your external SSO account.',
        'user_account_successfully_deleted' => 'Brugerkonto blev slettet',
        'has_lower_case' => 'Har små bogstaver',
        'has_upper_case' => 'Har store bogstaver',
        'has_special_char' => 'Har specialtegn',
        'has_number' => 'Har tal',
        'is_long_enough' => 'min. 8 karakterer',
        'mandatory_rules' => 'Obligatorisk',
        'optional_rules_you_should_follow' => 'Anbefalet (højt)',
        'caps_lock_is_on' => 'Caps lock er tændt',
    ],
    'sso_providers' => [
        'unknown' => 'ukendt',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
