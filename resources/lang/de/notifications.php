<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'hello' => 'Hallo',
    'hello_user' => 'Hallo :username,',
    'regards' => 'Mit freundlichen Grüßen',
    'test_email_settings' => [
        'subject' => '2FAuth-Test-E-Mail',
        'reason' => 'Sie erhalten diese E-Mail, weil Sie eine Test-E-Mail angefordert haben, um die E-Mail-Einstellungen Ihrer 2FAuth Instanz zu überprüfen.',
        'success' => 'Gute Nachricht, es funktioniert :)'
    ],
    'new_device' => [
        'subject' => 'Connection to 2FAuth from a new device',
        'resume' => 'Ein neues Gerät hat sich gerade mit Ihrem 2FAuth-Konto verbunden.',
        'connection_details' => 'Hier sind die Details dieser Verbindung',
        'recommandations' => 'Wenn dies auf Sie zutrifft, können Sie diese Meldung ignorieren. Wenn Sie eine verdächtige Aktivität in Ihrem Konto vermuten, ändern Sie bitte Ihr Passwort.'
    ],
    'failed_login' => [
        'subject' => 'Anmeldung bei 2FAuth fehlgeschlagen',
        'resume' => 'Es gab einen fehlgeschlagenen Anmeldeversuch auf Ihr 2FAuth Konto.',
        'connection_details' => 'Hier sind die Details zu diesem Verbindungsversuch',
        'recommandations' => 'Wenn Sie dies waren, können Sie diese Warnung ignorieren. Falls weitere Versuche fehlschlagen, sollten Sie den 2FAuth-Administrator kontaktieren, um die Sicherheitseinstellungen zu überprüfen und Maßnahmen gegen diesen Angreifer zu ergreifen.'
    ],
];