<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'settings' => 'Réglages',
    'account' => 'Compte',
    'password' => 'Mot de passe',
    'options' => 'Options',
    'confirm' => [

    ],
    'general' => 'General',
    'security' => 'Sécurité',
    'advanced' => 'Avancés',
    'forms' => [
        'edit_settings' => 'Modifier les réglages',
        'setting_saved' => 'Réglages sauvegardés',
        'language' => [
            'label' => 'Langue',
            'help' => 'Traduit l\'application dans la langue choisie'
        ],
        'show_token_as_dot' => [
            'label' => 'Rendre illisibles les codes générés',
            'help' => 'Remplace les caractères des codes générés par des ●●● pour garantir leur confidentialité. N\'affecte pas la fonction de copier/coller qui reste utilisable.'
        ],
        'close_token_on_copy' => [
            'label' => 'Ne plus afficher les codes copiés',
            'help' => 'Ferme automatiquement le popup affichant le code généré dès que ce dernier a été copié.'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Utiliser le lecteur de qrcode basique',
            'help' => 'Si vous rencontrez des problèmes lors de la lecture des qrCodes activez cette option pour utiliser un lecteur de qrcode moins évolué mais plus largement compatible'
        ],
        'display_mode' => [
            'label' => 'Mode d\'affichage',
            'help' => 'Change le mode d\'affichage des comptes, soit sous forme de liste, soit sous forme de grille'
        ],
        'grid' => 'Grille',
        'list' => 'Liste',
        'show_accounts_icons' => [
            'label' => 'Afficher les icônes',
            'help' => 'Affiche les icônes des comptes dans la vue principale'
        ],
        'auto_lock' => [
            'label' => 'Verouillage automatique',
            'help' => 'Déconnecter automatiquement l\'utilisateur en cas d\'inactivité'
        ],
        'never' => 'Jamais',
        'on_token_copy' => 'Après copie d\'un code de sécurité',
        '1_minutes' => 'Après 1 minute',
        '5_minutes' => 'Après 5 minutes',
        '10_minutes' => 'Après 10 minutes',
        '15_minutes' => 'Après 15 minutes',
        '30_minutes' => 'Après 15 minutes',
        '1_hour' => 'Après 1 heure',
        '1_day' => 'Après 1 journée',
    ],
    

];
