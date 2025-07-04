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
    'preferences' => 'Préférences',
    'account' => 'Compte',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Jetons',
    'options' => 'Options',
    'user_preferences' => 'Préférences utilisateur',
    'admin_settings' => 'Paramètres administrateur',
    'confirm' => [

    ],
    'you_are_administrator' => 'Vous êtes administrateur',
    'account_linked_to_sso_x_provider' => 'Vous vous êtes connecté via SSO avec votre compte :provider. Vos informations ne peuvent être modifiées ici mais sur :provider.',
    'general' => 'General',
    'security' => 'Sécurité',
    'notifications' => 'Notifications',
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
    'settings_managed_by_administrator' => 'Certains réglages sont gérés par votre administrateur',
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
        'timezone' => [
            'label' => 'Fuseau horaire',
            'help' => 'Fuseau horaire appliqué à toutes les dates et heures affichées dans l\'application'
        ],
        'show_otp_as_dot' => [
            'label' => 'Rendre illisibles les codes générés',
            'help' => 'Remplace les caractères des codes OTP générés par des ●●● pour garantir leur confidentialité. N\'affecte pas la fonction de copier/coller qui reste utilisable.'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Révéler les codes OTP illisibles',
            'help' => 'Permet à l\'utilisateur de réveler temporairement les code OTP dont les caractères ont été remplacés par des ●●●'
        ],
        'close_otp_on_copy' => [
            'label' => 'Fermer la vue OTP une fois le code copié',
            'help' => 'Le code OTP qui vient d\'être copié ne reste pas visible à l\'écran'
        ],
        'show_next_otp' => [
            'label' => 'Afficher le prochain code OTP',
            'help' => 'Prévisualiser le prochain code OTP, c\'est-à-dire le code qui remplacera le code actuel après son expiration. Les préférences définies pour le code OTP actuel s\'appliquent également au prochain code (mise en forme, affichage en point)'
        ],
        'auto_close_timeout' => [
            'label' => 'Fermeture automatique de la vue OTP',
            'help' => 'Cache automatiquement le code OTP à l\'écran après un certain temps. Cela évite les générations inutiles de nouveaux codes si la vue dédiée à leur affichage est restée ouverte.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Effacer la recherche après copie',
            'help' => 'Vide le champ de recherche dès qu\'un code a été copié dans le presse-papier'
        ],
        'sort_case_sensitive' => [
            'label' => 'Trier en tenant compte de la casse',
            'help' => 'Force la fonction de tri à tenir compte de la casse des lettres (majuscule/minuscule) pour ordonner les éléments'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copier le code OTP dès qu\'il s\'affiche',
            'help' => 'Copie automatiquement dans le presse-papier le code OTP qui vient de s\'afficher à l\'écran. A cause de restrictions des navigateurs, seul le premier code à s\'afficher est copié, pas les codes suivants'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Utiliser le lecteur de QR code basique',
            'help' => 'Si vous rencontrez des problèmes lors de la lecture des QR codes activez cette option pour utiliser un lecteur de QR code moins évolué mais plus largement compatible'
        ],
        'display_mode' => [
            'label' => 'Mode d\'affichage',
            'help' => 'Change le mode d\'affichage des comptes, soit sous forme de liste, soit sous forme de grille'
        ],
        'password_format' => [
            'label' => 'Mise en forme des codes OTP',
            'help' => 'Modifie l\'affichage des codes OTP en regroupement les chiffres afin de faciliter la lisibilité et leur mémorisation'
        ],
        'pair' => 'par Paire',
        'pair_legend' => 'Groupe les chiffres deux par deux',
        'trio_legend' => 'Groupe les chiffres trois par trois',
        'half_legend' => 'Sépare les chiffres en deux groupes égaux',
        'trio' => 'par Trio',
        'half' => 'par Moitié',
        'grid' => 'Grille',
        'list' => 'Liste',
        'theme' => [
            'label' => 'Thème',
            'help' => 'Forcer un thème spécifique ou appliquer le thème défini dans vos préférences système/navigateur'
        ],
        'light' => 'Clair',
        'dark' => 'Sombre',
        'automatic' => 'Auto',
        'show_accounts_icons' => [
            'label' => 'Afficher les icônes',
            'help' => 'Afficher les icônes des comptes dans la vue principale'
        ],
        'get_official_icons' => [
            'label' => 'Récupérer les icônes officielles',
            'help' => '(Essaie de) Récupère automatiquement l\'icône officielle du service émetteur du compte 2FA lors de son ajout à 2FAuth'
        ],
        'icon_collection' => [
            'label' => 'Collection d\'icônes favorite',
            'help' => 'La collection d\'icônes à interroger en priorité quand une icône doit être récupérée. La modification de ce paramètre n\'actualise pas les icônes déjà enregistrées avec vos comptes 2FA existants.'
        ],
        'icon_variant' => [
            'label' => 'Variante d\'icône',
            'help' => 'Certaines icônes sont disponibles dans plusieurs variantes qui vont s\'intégrer plus ou moins bien dans le thème clair ou dans le thème foncé. Définissez celle qui doit être récupérée en priorité. La variante standard sera automatiquement récupérée si celle spécifiée est indisponible.'
        ],
        'icon_variant_strict_fetch' => [
            'label' => 'Récupération stricte',
            'help' => 'Restreint la récupération d\'icône à la seule variante spécifiée. 2FAuth ne tentera pas de récupérer la variante standard à la place de la variante spécifiée lorsque celle-ci est indisponible.'
        ],
        'auto_lock' => [
            'label' => 'Verrouillage automatique',
            'help' => 'Déconnecte automatiquement l\'utilisateur en cas d\'inactivité. Est sans effet lorsque l\'authentification est gérée par un proxy et qu\'aucune URL de déconnexion personnalisée n\'est configurée.'
        ],
        'default_group' => [
            'label' => 'Groupe par défaut',
            'help' => 'Le groupe auquel sont associés les nouveaux comptes',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Afficher le groupe par défaut après copie',
            'help' => 'Bascule systématiquement l\'affichage sur le groupe par défaut dès qu\'un code a été copié dans le presse-papier',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Sauvegarde automatique des comptes',
            'help' => 'Les nouveaux comptes sont automatiquement enregistrés après le scan ou le téléchargement d\'un code QR, il n\'est plus nécessaire de cliquer sur le bouton Enregistrer',
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
        'otp_generation' => [
            'label' => 'Affichage des codes OTP',
            'help' => 'Définit quand et comment sont affichés les <abbr title="One-Time Passwords">OTPs</abbr>.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'Pour un nouvel appareil',
            'help' => 'Recevez un email quand un nouvel appareil se connecte à votre compte 2FAuth pour la première fois'
        ],
        'notify_on_failed_login' => [
            'label' => 'Après une connexion échouée',
            'help' => 'Recevez un e-mail chaque fois qu\'une tentative de connexion à votre compte 2FAuth échoue'
        ],
        'show_email_in_footer' => [
            'label' => 'Afficher l\'email dans le pied de page',
            'help' => 'Affiche l\'email de l\'utilisateur connecté dans le pied de page, en lieu et place des liens de navigation. Les liens sont alors accessibles dans un menu de navigation que l\'on affiche en cliquant/tapotant sur l\'email.'
        ],
        'otp_generation_on_request' => 'Après un clic/tap',
        'otp_generation_on_request_legend' => 'Seul, dans un écran dédié',
        'otp_generation_on_request_title' => 'Cliquer sur un compte pour obtenir un code OTP dans un écran dédié',
        'otp_generation_on_home' => 'En permanence',
        'otp_generation_on_home_legend' => 'Tous, sur l\'écran d\'accueil',
        'otp_generation_on_home_title' => 'Montrer tous les codes OTP sur l\'écran d\'accueil sans aucune action de l\'utilisateur',
        'never' => 'Jamais',
        'on_otp_copy' => 'Après copie d\'un code OTP',
        '1_minutes' => 'Après 1 minute',
        '2_minutes' => 'Après 2 minutes',
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