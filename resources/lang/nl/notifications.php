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
    'regards' => 'Groeten',
    'test_email_settings' => [
        'subject' => '2FAuth test e-mail',
        'reason' => 'Je ontvangt deze e-mail omdat je een test e-mail hebt aangevraagd om de e-mailinstellingen van je 2FAuth installatie te valideren.',
        'success' => 'Goed nieuws, het werkt :)'
    ],
    'new_device' => [
        'subject' => 'Verbinding met 2FAuth vanaf een nieuw apparaat',
        'resume' => 'Een nieuw apparaat heeft zojuist verbinding gemaakt met je 2FAuth account.',
        'connection_details' => 'Hier zijn de details van deze verbinding',
        'recommandations' => 'Als u dit was, kunt u deze waarschuwing negeren. Als u vermoedt dat er sprake is van een verdachte activiteit op uw account, wijzig dan uw wachtwoord.'
    ],
    'failed_login' => [
        'subject' => 'Mislukte login naar 2FAuth',
        'resume' => 'Er is een mislukte inlog poging gedaan voor je 2FAuth account.',
        'connection_details' => 'Hier zijn de details van deze verbindingspoging',
        'recommandations' => 'Als jij dit was, kan je deze melding negeren. Als verdere pogingen mislukken, moet u contact opnemen met de 2FAuth beheerder om de beveiligingsinstellingen te bekijken en actie ondernemen tegen deze aanvaller.'
    ],
];