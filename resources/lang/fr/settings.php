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
            'label' => 'Mode d\'affichage Desktop',
            'help' => 'Change la représentation des comptes, soit sous forme de liste, soit sous forme de grille'
        ],
        'grid' => 'Grille',
        'list' => 'Liste',

    ],
    

];
