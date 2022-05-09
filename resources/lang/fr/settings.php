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
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Jetons',
    'options' => 'Options',
    'confirm' => [

    ],
    'general' => 'General',
    'security' => 'Sécurité',
    'profile' => 'Profil',
    'change_password' => 'Changer le mot de passe',
    'personal_access_tokens' => 'Jetons d\'accès personnel',
    'token_legend' => 'Les jetons d\'accès personnels permettent à n\'importe quelle application de s\'authentifier à l\'API 2Fauth. Vous devez fournir le jeton d\'accès en tant que Bearer dans l\'en-tête d\'autorisation des requêtes de ces applications.',
    'generate_new_token' => 'Générer un nouveau jeton',
    'revoke' => 'Révoquer',
    'token_revoked' => 'Jeton révoqué avec succès',
    'revoking_a_token_is_permanent' => 'Révoquer un jeton est définitif',
    'confirm' => [
        'revoke' => 'Êtes-vous sûr(e) de vouloir révoquer ce jeton ?',
    ],
    'make_sure_copy_token' => 'Copier votre jeton d\'accès personnel dès maintenant. Vous ne pourrez pas l\'afficher à nouveau !',
    'data_input' => 'Saisie des données',
    'forms' => [
        'edit_settings' => 'Modifier les réglages',
        'setting_saved' => 'Réglages sauvegardés',
        'new_token' => 'Nouveau jeton',
        'some_translation_are_missing' => 'Certaines traductions sont manquantes en utilisant la langue préférée du navigateur ?',
        'help_translate_2fauth' => 'Aidez à traduire 2FAuth',
        'language' => [
            'label' => 'Langue',
            'help' => 'Langue utilisée pour traduire l\'interface utilisateur de 2FAuth. Les langues proposées sont complètes, vous pouvez les utiliser pour remplacer la langue de référence de votre navigateur.'
        ],
        'show_otp_as_dot' => [
            'label' => 'Afficher les mots de passe générés sous forme de point',
            'help' => 'Remplace les caractères des mots de passe générés par des ●●● pour garantir leur confidentialité. N\'affecte pas la fonction de copier/coller qui reste utilisable.'
        ],
        'close_otp_on_copy' => [
            'label' => 'Ne plus afficher les mots de passe copiés',
            'help' => 'Ferme automatiquement le popup affichant le mot de passe généré dès que ce dernier a été copié'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Utiliser le lecteur de QR code basique',
            'help' => 'Si vous rencontrez des problèmes lors de la lecture des QR codes activez cette option pour utiliser un lecteur de QR code moins évolué mais plus largement compatible'
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
            'label' => 'Verrouillage automatique',
            'help' => 'Déconnecter automatiquement l\'utilisateur en cas d\'inactivité'
        ],
        'use_encryption' => [
            'label' => 'Protéger les données sensibles',
            'help' => 'Les données sensibles, les secrets et les e-mails 2FA, sont stockés chiffrés dans la base de données. Assurez-vous de sauvegarder la valeur APP_KEY de votre fichier env (ou tout le fichier) car il sert de clé de chiffrement. Il n\'y a aucun moyen de déchiffrer les données chiffrées sans cette clé.',
        ],
        'default_group' => [
            'label' => 'Groupe par défaut',
            'help' => 'Le groupe auquel sont associés les nouveaux comptes',
        ],
        'useDirectCapture' => [
            'label' => 'Saisie directe',
            'help' => 'Choisissez si vous voulez être invité à choisir un mode de saisie parmi ceux disponibles ou si vous voulez utiliser directement le mode de saisie par défaut',
        ],
        'defaultCaptureMode' => [
            'label' => 'Mode de saisie par défaut',
            'help' => 'Mode de saisie utilisé par défaut lorsque l\'option Saisie directe est activée',
        ],
        'remember_active_group' => [
            'label' => 'Mémoriser le filtrage par groupe',
            'help' => 'Enregistre le dernier groupe affiché et le restaure lors de votre prochaine visite',
        ],
        'never' => 'Jamais',
        'on_otp_copy' => 'Après copie d\'un mot de passe',
        '1_minutes' => 'Après 1 minute',
        '5_minutes' => 'Après 5 minutes',
        '10_minutes' => 'Après 10 minutes',
        '15_minutes' => 'Après 15 minutes',
        '30_minutes' => 'Après 30 minutes',
        '1_hour' => 'Après 1 heure',
        '1_day' => 'Après 1 journée',
        'livescan' => 'Scanner avec la caméra',
        'upload' => 'Téléchargement de QR code',
        'advanced_form' => 'Formulaire avancé',
    ],

];