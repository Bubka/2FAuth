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

    'service' => 'Service',
    'account' => 'Compte',
    'icon' => 'Icône',
    'new' => 'Nouveau',
    'no_account_here' => 'Aucun compte 2FA !',
    'add_first_account' => 'Ajouter votre premier compte',
    'use_full_form' => 'Ou utiliser le formulaire détaillé',
    'add_one' => 'Add one',
    'manage' => 'Gérer',
    'done' => 'Terminé',
    'forms' => [
        'service' => [
            'placeholder' => 'example.com',
        ],
        'account' => [
            'placeholder' => 'Marc Dupont',
        ],
        'new_account' => 'Nouveau compte',
        'edit_account' => 'Modifier le compte',
        'otp_uri' => 'OTP Uri',
        'hotp_counter' => 'Compteur HOTP',
        'scan_qrcode' => 'Scanner un QR code',
        'use_qrcode' => [
            'val' => 'Utiliser un QR code',
            'title' => 'Utiliser un QR code pour renseigner le formulaire d\'un seul coup d\'un seul'
        ],
        'unlock' => [
            'val' => 'Déverouiller',
            'title' => 'Déverouiller le champ (à vos risques et périls)',
        ],
        'lock' => [
            'val' => 'Vérouiller',
            'title' => 'Vérouiller le champ',
        ],
        'choose_image' => 'Choisir une image…',
        'create' => 'Créer',
        'save' => 'Enregistrer',
        'test' => 'Tester',
    ],
    'stream' => [
        'need_grant_permission' => 'Vous devez autoriser l\'utilisation de votre caméra',
        'not_readable' => 'Le scanner ne se charge pas. La caméra est-elle déjà utilisée ?',
        'no_cam_on_device' => 'Votre équipement ne dispose pas de caméra',
        'secured_context_required' => 'Contexte sécurisé requis (HTTPS ou localhost)',
        'camera_not_suitable' => 'Votre équipement ne dispose pas d\'une caméra adaptée',
        'stream_api_not_supported' => 'L\'API Stream n\'est pas supportée par votre navigateur'
    ],
    'confirm' => [
        'delete' => 'Etes-vous sûrs de vouloir supprimer le compte ?',
        'cancel' => 'Les données seront perdues, êtes-vous sûrs ?'
    ],

];