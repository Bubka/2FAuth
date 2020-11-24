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
    'accounts' => 'Comptes',
    'icon' => 'Icône',
    'no_account_here' => 'Aucun compte 2FA !',
    'add_first_account' => 'Ajouter votre premier compte',
    'use_full_form' => 'Ou utiliser le formulaire détaillé',
    'add_one' => 'Add one',
    'show_qrcode' => 'Afficher le QR code',
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
        'scan_qrcode' => 'Scanner un QR code',
        'upload_qrcode' => 'Uploader un QR code',
        'use_advanced_form' => 'Utiliser le formulaire avancé',
        'prefill_using_qrcode' => 'Préremplir à l\'aide d\'un QR Code',
        'use_qrcode' => [
            'val' => 'Utiliser un QR code',
            'title' => 'Utiliser un QR code pour renseigner le formulaire d\'un seul coup d\'un seul',
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
        'test' => 'Tester',
        'secret' => [
            'label' => 'Secret',
            'help' => 'La clé utilisée pour générer vos codes de sécurité'
        ],
        'plain_text' => 'Texte brut',
        'otp_type' => [
            'label' => 'Choisissez le type d\'OTP à créer',
            'help' => 'Time-based OTP ou HMAC-based OTP'
        ],
        'digits' => [
            'label' => 'Nombre de chiffres',
            'help' => 'Le nombre de chiffres des codes de sécurité générés'
        ],
        'algorithm' => [
            'label' => 'Algorithme',
            'help' => 'L\'algorithme utilisé pour sécuriser vos codes de sécurité'
        ],
        'totpPeriod' => [
            'label' => 'Durée de validité',
            'placeholder' => '30s par défaut',
            'help' => 'La durée de validité des codes de sécurité générés, en seconde'
        ],
        'hotpCounter' => [
            'label' => 'Compteur',
            'placeholder' => '0 par défaut',
            'help' => 'La valeur initiale du compteur',
            'help_lock' => 'Il est risqué de modifier le compteur car vous pouvez désynchroniser le compte avec le serveur de vérification du service. Utilisez l\'icône cadenas pour activer la modification, mais seulement si vous savez ce que vous faites'
        ],
        'image_link' => [
            'label' => 'Image',
            'placeholder' => 'http://...',
            'help' => 'L\'url d\'une image externe à utiliser comme icône du compte'
        ],
        'options_help' => 'Vous pouvez laisser les options suivantes non renseignées si vous ne savez pas comment les définir. Les valeurs les plus couramment utilisées seront appliquées.',
        'alternative_methods' => 'Méthodes alternatives',
    ],
    'stream' => [
        'live_scan_cant_start' => 'Le scanner ne peut pas démarrer :(',
        'need_grant_permission' => [
            'reason' => '2FAuth n\'a pas la permission d\'accéder à votre caméra',
            'solution' => 'Vous devez autoriser l\'utilisation de l\'appareil photo de votre appareil. Si vous avez déjà refusé et que votre navigateur ne vous le demande plus, veuillez vous référer à la documentation du navigateur pour savoir comment accorder l’autorisation.'
        ],
        'not_readable' => [
            'reason' => 'Impossible de charger le scanner',
            'solution' => 'La caméra est-elle déjà en cours d\'utilisation ? Assurez-vous qu\'aucune autre application n\'utilise votre appareil photo et réessayez'
        ],
        'no_cam_on_device' => [
            'reason' => 'Votre équipement ne dispose pas de caméra',
            'solution' => 'Peut-être avez-vous oublié de brancher votre webcam'
        ],
        'secured_context_required' => [
            'reason' => 'Contexte sécurisé requis',
            'solution' => 'Une connexion sécurisée HTTPS est requise pour utiliser le scanner. Si vous exécutez 2FAuth depuis votre ordinateur, n\'utilisez pas d\'hôte virtuel autre que localhost'
        ],
        'https_required' => 'HTTPS requis pour utiliser la caméra',
        'camera_not_suitable' => [
            'reason' => 'Votre équipement ne dispose pas d\'une caméra adaptée',
            'solution' => 'Veuillez utiliser un autre appareil'
        ],
        'stream_api_not_supported' => [
            'reason' => 'L\'API Stream n\'est pas prise en charge dans ce navigateur',
            'solution' => 'Vous devriez utiliser un navigateur moderne'
        ],
    ],
    'confirm' => [
        'delete' => 'Etes-vous sûrs de vouloir supprimer le compte ?',
        'cancel' => 'Les données seront perdues, êtes-vous sûrs ?'
    ],

];