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

    'hello' => 'Bonjour',
    'hello_user' => 'Bonjour :username,',
    'regards' => 'Cordialement',
    'test_email_settings' => [
        'subject' => 'Email de test 2FAuth',
        'reason' => 'Vous recevez cet email car vous avez demandé à vérifier le bon fonctionnement de l\'envoi des emails sur votre instance 2FAuth.',
        'success' => 'Bonne nouvelle, ça fonctionne :)'
    ],
    'new_device' => [
        'subject' => 'Connexion à 2FAuth depuis un nouvel appareil',
        'resume' => 'Un nouvel appareil vient de se connecter à votre compte 2FAuth.',
        'connection_details' => 'Voici les détails de cette connexion',
        'recommandations' => 'S\'il s\'agit de vous, vous pouvez ignorer cette alerte. Dans le cas contraire, et si vous soupçonnez une activité suspecte sur votre compte, changez votre mot de passe sans attendre.'
    ],
    'failed_login' => [
        'subject' => 'Échec de connexion à 2FAuth',
        'resume' => 'Une tentative de connexion à votre compte 2FAuth a eu lieu mais a été rejetée.',
        'connection_details' => 'Voici les détails de cette tentative de connexion',
        'recommandations' => 'S\'il s\'agit de vous, vous pouvez ignorer cette alerte. Si d\'autres tentatives infructueuses se produisent, vous devriez contacter l\'administrateur de 2FAuth pour examiner les paramètres de sécurité et prendre des mesures contre cet attaquant.'
    ],
];