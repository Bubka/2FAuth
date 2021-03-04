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
    'throttle' => 'Zu viele Loginversuche. Versuchen Sie es bitte in :seconds Sekunden nochmal.',

    // 2FAuth
    'sign_out' => 'Abmelden',
    'sign_in' => 'Anmelden',
    'register' => 'Registrieren',
    'welcome_back_x' => 'Willkommen zurück, {0}',
    'already_authenticated' => 'Bereits angemeldet',
    'confirm' => [
        'logout' => 'Sind Sie sicher, dass Sie sich abmelden möchten?',
    ],
    'forms' => [
        'name' => 'Name',
        'login' => 'Anmeldung',
        'email' => 'E-Mail',
        'password' => 'Passwort',
        'confirm_password' => 'Passwort bestätigen',
        'confirm_new_password' => 'Neues Passwort bestätigen',
        'dont_have_account_yet' => 'Sie haben noch keinen Account?',
        'already_register' => 'Schon registriert?',
        'password_do_not_match' => 'Passwörter stimmen nicht überein',
        'forgot_your_password' => 'Passwort vergessen?',
        'request_password_reset' => 'Zurücksetzen',
        'reset_password' => 'Password zurücksetzen',
        'no_reset_password_in_demo' => 'Zurücksetzen im Demo-Modus nicht möglich',
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
        'welcome_to_demo_app_use_those_credentials' => 'Willkommen bei der 2FAuth Demo.<br><br>Sie können sich mit der E-Mail-Adresse <strong>demo@2fauth.app</strong> und dem Passwort <strong>demo</demo> anmelden',
        'register_punchline' => 'Willkommen bei 2FAuth.<br/>Du benötigst einen Account, um weiterzumachen. Füllen Sie dieses Formular aus, um sich zu registrieren, und wählen Sie bitte ein starkes Passwort, denn 2FA-Daten sind sensibel.',
        'reset_punchline' => '2FAuth sendet Ihnen einen Link zum Zurücksetzen des Passworts an diese Adresse. Klicken Sie auf den Link in der erhaltenen E-Mail, um ein neues Passwort festzulegen.',
    ],

];
