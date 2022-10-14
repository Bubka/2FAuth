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
    'failed' => 'Diese Kombination aus Zugangsdaten wurde nicht in unserer Datenbank gefunden.',
    'password' => 'Das Passwort ist falsch.',
    'throttle' => 'Zu viele Loginversuche. Versuchen Sie es bitte in :seconds Sekunden nochmal.',

    // 2FAuth
    'sign_out' => 'Abmelden',
    'sign_in' => 'Anmelden',
    'sign_in_using' => 'Anmelden mit',
    'sign_in_using_security_device' => 'Sign in using a security device',
    'login_and_password' => 'benutzername & passwort',
    'register' => 'Registrieren',
    'welcome_back_x' => 'Willkommen zurück, {0}',
    'autolock_triggered' => 'Automatische Sperre ausgelöst',
    'autolock_triggered_punchline' => 'The event watched by the Auto Lock feature has fired. You\'ve been automatically disconnected.',
    'change_autolock_in_settings' => 'You can change the behavior of the Autolock feature in Settings > Options tab.',
    'already_authenticated' => 'Bereits angemeldet',
    'authentication' => 'Authentication',
    'maybe_later' => 'Vielleicht später',
    'user_account_controlled_by_proxy' => 'User account made available by an authentication proxy.<br />Manage the account at proxy level.',
    'auth_handled_by_proxy' => 'Authentication handled by a reverse proxy, below settings are disabled.<br />Manage authentication at proxy level.',
    'confirm' => [
        'logout' => 'Sind Sie sicher, dass Sie sich abmelden möchten?',
        'revoke_device' => 'Are you sure you want to revoke this device?',
        'delete_account' => 'Are you sure you want to delete your account?',
    ],
    'webauthn' => [
        'security_device' => 'a security device',
        'security_devices' => 'Sicherheitsgeräte',
        'security_devices_legend' => 'Authentication devices you can use to sign in 2FAuth, like security keys (i.e Yubikey) or smartphones with biometric capabilities (i.e. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'You can enhance the security of your 2FAuth account by enabling WebAuthn authentication.<br /><br />
            WebAuthn allows you to use trusted devices (like Yubikeys or smartphones with biometric capabilities) to sign in quickly and more securely.',
        'use_security_device_to_sign_in' => 'Get ready to authenticate using (one of) your security devices. Plug your key in, remove face mask or gloves, etc.',
        'lost_your_device' => 'Haben Sie Ihr Gerät verloren?',
        'recover_your_account' => 'Konto wiederherstellen',
        'account_recovery' => 'Kontowiederherstellung',
        'recovery_punchline' => '2FAuth will send you a recovery link to this email address. Click the link in the received email to register a new security device.<br /><br />Ensure you open the email on a device you fully own.',
        'send_recovery_link' => 'Send recovery link',
        'account_recovery_email_sent' => 'Account recovery email sent!',
        'disable_all_other_devices' => 'Disable all other devices except this one',
        'register_a_new_device' => 'Register a new device',
        'register_a_device' => 'Register a device',
        'device_successfully_registered' => 'Device successfully registered',
        'device_revoked' => 'Device successfully revoked',
        'revoking_a_device_is_permanent' => 'Revoking a device is permanent',
        'recover_account_instructions' => 'Click the button below to register a new security device to recover your account. Just follow your browser instructions.',
        'invalid_recovery_token' => 'Invalid recovery token',
        'rename_device' => 'Rename device',
        'my_device' => 'My device',
        'unknown_device' => 'Unknown device',
        'use_webauthn_only' => [
            'label' => 'Use WebAuthn only',
            'help' => 'Make WebAuthn the only available method to sign in 2FAuth. This is the recommended setup to take advantage of the WebAuthn enhanced security.<br />
                In case of device lost you will always be able to register a new security device to recover your account.'
        ],
        'need_a_security_device_to_enable_options' => 'Fügen Sie mindestens ein Gerät hinzu, um diese Optionen zu aktivieren',
        'use_webauthn_as_default' => [
            'label' => 'Use WebAuthn as default sign in method',
            'help' => 'Set the 2FAuth sign in form to propose the WebAuthn authentication at first. The Login/password method is then available as an alternative/fallback solution.<br />
                This has no effect if you only use WebAuthn.'
        ],
    ],
    'forms' => [
        'name' => 'Name',
        'login' => 'Anmeldung',
        'webauthn_login' => 'WebAuthn Anmeldung',
        'email' => 'E-Mail',
        'password' => 'Passwort',
        'reveal_password' => 'Reveal password',
        'hide_password' => 'Hide password',
        'confirm_password' => 'Passwort bestätigen',
        'confirm_new_password' => 'Neues Passwort bestätigen',
        'dont_have_account_yet' => 'Sie haben noch keinen Account?',
        'already_register' => 'Schon registriert?',
        'authentication_failed' => 'Anmeldung fehlgeschlagen',
        'forgot_your_password' => 'Passwort vergessen?',
        'request_password_reset' => 'Zurücksetzen',
        'reset_your_password' => 'Reset your password',
        'reset_password' => 'Password zurücksetzen',
        'disabled_in_demo' => 'Funktion ist im Demo-Modus deaktiviert',
        'new_password' => 'Neues Passwort',
        'current_password' => [
            'label' => 'Aktuelles Passwort',
            'help' => 'Geben Sie Ihr aktuelles Passwort ein, um zu bestätigen, dass Sie es sind'
        ],
        'change_password' => 'Passwort ändern',
        'send_password_reset_link' => 'Link zum Zurücksetzen des Passworts senden',
        'password_successfully_changed' => 'Passwort erfolgreich geändert',
        'edit_account' => 'Account bearbeiten',
        'profile_saved' => 'Profil erfolgreich aktualisiert!',
        'welcome_to_demo_app_use_those_credentials' => 'Willkommen bei der 2FAuth Demo.<br><br>Sie können sich mit der E-Mail-Adresse <strong>demo@2fauth.app</strong> und dem Passwort <strong>demo</strong> anmelden',
        'welcome_to_testing_app_use_those_credentials' => 'Willkommen bei der 2FAuth Testinstanz.<br><br>Verwenden Sie die E-Mail-Adresse <strong>testing@2fauth.app</strong> und das Passwort <strong>password</strong>',
        'register_punchline' => 'Welcome to <b>2FAuth</b>.<br/>You need an account to go further, please register yourself.',
        'reset_punchline' => '2FAuth sendet Ihnen einen Link zum Zurücksetzen des Passworts an diese Adresse. Klicken Sie auf den Link in der erhaltenen E-Mail, um ein neues Passwort festzulegen.',
        'name_this_device' => 'Dieses Gerät benennen',
        'delete_account' => 'Konto löschen',
        'delete_your_account' => 'Ihr Konto Löschen',
        'delete_your_account_and_reset_all_data' => 'Dies wird 2FAuth zurücksetzen. Ihr Benutzerkonto sowie alle 2FA-Daten werden gelöscht. Dies kann nicht rückgängig gemacht werden.',
        'user_account_successfully_deleted' => 'Benutzerkonto wurde erfolgreich gelöscht',
        'has_lower_case' => 'Has lower case',
        'has_upper_case' => 'Has upper case',
        'has_special_char' => 'Has special char',
        'has_number' => 'Has number',
        'is_long_enough' => '8 characters min.',
        'mandatory_rules' => 'Mandatory',
        'optional_rules_you_should_follow' => 'Recommanded (highly)',
        'caps_lock_is_on' => 'Caps lock is On',
    ],

];
