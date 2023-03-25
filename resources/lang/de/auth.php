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
    'sign_in_using_security_device' => 'Mit einem Sicherheitsgerät anmelden',
    'login_and_password' => 'benutzername & passwort',
    'register' => 'Registrieren',
    'welcome_to_2fauth' => 'Welcome to 2FAuth',
    'autolock_triggered' => 'Automatische Sperre ausgelöst',
    'autolock_triggered_punchline' => 'Das Ereignis, das von der Auto-Lock-Funktion beobachtet wird, wurde gestartet. Sie wurden automatisch getrennt.',
    'change_autolock_in_settings' => 'Sie können das Verhalten der Autolock-Funktion unter Einstellungen > Optionen ändern.',
    'already_authenticated' => 'Bereits angemeldet',
    'authentication' => 'Authentifizierung',
    'maybe_later' => 'Vielleicht später',
    'user_account_controlled_by_proxy' => 'Benutzerkonto, das von einem Authentifizierungsproxy zur Verfügung gestellt wurde.<br />Verwalte das Konto auf Proxy-Ebene.',
    'auth_handled_by_proxy' => 'Authentifizierung von einem Reverse-Proxy verwaltet, unten sind die Einstellungen deaktiviert.<br />Authentifizierung auf Proxy-Ebene verwalten.',
    'confirm' => [
        'logout' => 'Sind Sie sicher, dass Sie sich abmelden möchten?',
        'revoke_device' => 'Möchten Sie das Gerät wirklich entfernen?',
        'delete_account' => 'Möchten Sie Ihr Konto wirklich löschen?',
    ],
    'webauthn' => [
        'security_device' => 'ein Sicherheitsgerät',
        'security_devices' => 'Sicherheitsgeräte',
        'security_devices_legend' => 'Authentifizierungsgeräte, mit denen Sie sich in 2FAuth anmelden können, wie z.B. Sicherheitsschlüssel (z.B. Yubikey) oder Smartphones mit biometrischen Fähigkeiten (z.B. Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'You can enhance the security of your 2FAuth account by enabling WebAuthn authentication.<br /><br />
            WebAuthn allows you to use trusted devices (like Yubikeys or smartphones with biometric capabilities) to sign in quickly and more securely.',
        'use_security_device_to_sign_in' => 'Get ready to authenticate using (one of) your security devices. Plug your key in, remove face mask or gloves, etc.',
        'lost_your_device' => 'Haben Sie Ihr Gerät verloren?',
        'recover_your_account' => 'Konto wiederherstellen',
        'account_recovery' => 'Kontowiederherstellung',
        'recovery_punchline' => '2FAuth will send you a recovery link to this email address. Click the link in the received email and follow the instructions.<br /><br />Ensure you open the email on a device you fully own.',
        'send_recovery_link' => 'Send recovery link',
        'account_recovery_email_sent' => 'Account recovery email sent!',
        'disable_all_security_devices' => 'Disable all security devices',
        'disable_all_security_devices_help' => 'All your security devices will be revoked. Use this option if you have lost one or its security has been compromised.',
        'register_a_new_device' => 'Register a new device',
        'register_a_device' => 'Register a device',
        'device_successfully_registered' => 'Device successfully registered',
        'device_revoked' => 'Device successfully revoked',
        'revoking_a_device_is_permanent' => 'Revoking a device is permanent',
        'recover_account_instructions' => 'To recover your account, 2FAuth resets some Webauthn settings so you will be able to sign in using your email and password.',
        'invalid_recovery_token' => 'Invalid recovery token',
        'webauthn_login_disabled' => 'Webauthn login disabled',
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
