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
    'failed' => 'Ces identifiants ne correspondent pas à nos enregistrements',
    'password' => 'Le mot de passe saisi est incorrect.',
    'throttle' => 'Tentatives de connexion trop nombreuses. Veuillez essayer de nouveau dans :seconds secondes.',

    // 2FAuth
    'sign_out' => 'Déconnexion',
    'sign_in' => 'Se connecter',
    'sign_in_using' => 'Se connecter en utilisant',
    'sign_in_using_security_device' => 'Se connecter en utilisant un périphérique de sécurité',
    'login_and_password' => 'login & mot de passe',
    'register' => 'Créer un compte',
    'welcome_to_2fauth' => 'Bienvenue sur 2FAuth',
    'autolock_triggered' => 'Verrouillage automatique déclenché',
    'autolock_triggered_punchline' => 'L\'événement surveillé par la fonction de verrouillage automatique s\'est déclenché. Vous avez été automatiquement déconnecté.',
    'change_autolock_in_settings' => 'Vous pouvez modifier le comportement de la fonction de verrouillage automatique dans Réglages, onglet Options.',
    'already_authenticated' => 'Déjà authentifié',
    'authentication' => 'Authentification',
    'maybe_later' => 'Peut-être plus tard',
    'user_account_controlled_by_proxy' => 'Compte utilisateur mis à disposition par un proxy d\'authentification.<br />Gérer le compte au niveau du proxy.',
    'auth_handled_by_proxy' => 'Authentification gérée par un proxy inverse, les paramètres ci-dessous sont désactivés.<br />Gérer l\'authentification au niveau du proxy.',
    'confirm' => [
        'logout' => 'Etes-vous sûrs de vouloir vous déconnecter ?',
        'revoke_device' => 'Voulez-vous vraiment supprimer cet appareil ?',
        'delete_account' => 'Voulez-vous vraiment supprimer votre compte ?',
    ],
    'webauthn' => [
        'security_device' => 'un périphérique de sécurité',
        'security_devices' => 'Périphériques de sécurité',
        'security_devices_legend' => 'Périphériques d\'authentification que vous pouvez utiliser pour vous connecter à 2FAuth, comme des clés de sécurité (ex : YubiKey) ou des smartphones dotés de capacités biométriques (ex : Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'Vous pouvez améliorer la sécurité de votre compte 2FAuth en activant l\'authentification WebAuthn.<br /><br />
            WebAuthn vous permet d\'utiliser des appareils de confiance (comme des Yubikeys ou des smartphones dotés de capacités biométriques) pour vous connecter rapidement et de manière plus sécurisée.',
        'use_security_device_to_sign_in' => 'Préparez-vous à vous authentifier en utilisant un de vos dispositifs de sécurité. Branchez votre clé, retirez votre masque ou vos gants, etc.',
        'lost_your_device' => 'Vous avez perdu votre appareil ?',
        'recover_your_account' => 'Récupérer votre compte',
        'account_recovery' => 'Récupération du compte',
        'recovery_punchline' => '2FAuth vous enverra un lien de récupération à cette adresse e-mail. Cliquez sur le lien et suivez les instructions pour restaurer l\'accès à votre compte.<br /><br />Assurez-vous d\'ouvrir l\'e-mail depuis un appareil qui vous appartient ou que vous considérez de confiance.',
        'send_recovery_link' => 'Envoyer le lien de récupération',
        'account_recovery_email_sent' => 'E-mail de récupération du compte envoyé !',
        'disable_all_security_devices' => 'Désactiver tous les périphériques de sécurité',
        'disable_all_security_devices_help' => 'Tous vos périphériques de sécurité seront révoqués. Utilisez cette option si vous avez perdu un périphérique ou si sa sécurité a été compromise.',
        'register_a_new_device' => 'Ajouter un nouveau périphérique',
        'register_a_device' => 'Ajouter un périphérique',
        'device_successfully_registered' => 'Périphérique enregistré avec succès',
        'device_revoked' => 'Périphérique révoqué avec succès',
        'revoking_a_device_is_permanent' => 'La révocation d\'un périphérique est définitive',
        'recover_account_instructions' => 'Pour récupérer votre compte, 2FAuth réinitialise certains paramètres Webauthn afin que vous puissiez vous connecter en utilisant votre adresse e-mail et votre mot de passe.',
        'invalid_recovery_token' => 'Jeton de sécurité invalide',
        'webauthn_login_disabled' => 'Connexion Webauthn désactivée',
        'invalid_reset_token' => 'Ce jeton de réinitialisation n\'est pas valide.',
        'rename_device' => 'Renommer le périphérique',
        'my_device' => 'Mon périphérique',
        'unknown_device' => 'Périphérique inconnu',
        'use_webauthn_only' => [
            'label' => 'Utiliser uniquement WebAuthn',
            'help' => 'Faire de WebAuthn la seule méthode autorisée pour vous connecter à votre compte 2FAuth. Ceci est la configuration recommandée pour profiter de la sécurité améliorée de WebAuthn.<br /><br />
                En cas de perte de votre périphérique, vous pourrez récupérer votre compte en réinitialisant cette option et en vous connectant à l\'aide de votre adresse e-mail et de votre mot de passe.<br /><br />
                Attention ! Le formulaire E-mail + Mot de passe restera disponible même si l\'option est activée, mais il retournera systématiquement une réponse \'Échec de l\'authentification\'.'
        ],
        'need_a_security_device_to_enable_options' => 'Définissez au moins un périphérique pour activer ces options',
    ],
    'forms' => [
        'name' => 'Nom',
        'login' => 'Connexion',
        'webauthn_login' => 'Connexion WebAuthn',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'reveal_password' => 'Afficher le mot de passe',
        'hide_password' => 'Masquer le mot de passe',
        'confirm_password' => 'Confirmez le mot de passe',
        'confirm_new_password' => 'Confirmez le nouveau mot de passe',
        'dont_have_account_yet' => 'Pas encore de compte ?',
        'already_register' => 'Déjà enregistré ?',
        'authentication_failed' => 'Échec de l\'authentification',
        'forgot_your_password' => 'Mot de passe oublié ?',
        'request_password_reset' => 'Réinitialisez-le',
        'reset_your_password' => 'Réinitialiser votre mot de passe',
        'reset_password' => 'Mot de passe oublié',
        'disabled_in_demo' => 'Fonctionnalité désactivée en mode Démo',
        'new_password' => 'Nouveau mot de passe',
        'current_password' => [
            'label' => 'Mot de passe actuel',
            'help' => 'Indiquez votre mot de passe actuel pour confirmer qu\'il s\'agit bien de vous'
        ],
        'change_password' => 'Modifier le mot de passe',
        'send_password_reset_link' => 'Envoyer',
        'password_successfully_changed' => 'Mot de passe modifié avec succès',
        'edit_account' => 'Mis à jour du profil',
        'profile_saved' => 'Profil mis à jour avec succès !',
        'welcome_to_demo_app_use_those_credentials' => 'Bienvenue sur la démo de 2FAuth.<br><br>Vous pouvez vous connecter en utilisant l\'adresse email <strong>demo@2fauth.app</strong> et le mot de passe <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Bienvenue sur l\'instance de test 2FAuth.<br><br>Utilisez l\'adresse e-mail <strong>testing@2fauth.app</strong> et mot de passe <strong>password</strong>',
        'register_punchline' => 'Bienvenue sur <b>2FAuth</b>.<br/>Vous avez besoin d\'un compte pour aller plus loin, veuillez vous enregistrer.',
        'reset_punchline' => '2FAuth vous enverra un lien de réinitialisation de mot de passe à cette adresse. Cliquez sur le lien dans l\'e-mail reçu pour définir un nouveau mot de passe.',
        'name_this_device' => 'Nommer ce périphérique',
        'delete_account' => 'Supprimer le compte',
        'delete_your_account' => 'Supprimer votre compte',
        'delete_your_account_and_reset_all_data' => 'Cela réinitialisera 2FAuth. Votre compte utilisateur sera supprimé ainsi que toutes les données 2FA. Il est impossible de restaurer des données réinitialisées.',
        'user_account_successfully_deleted' => 'Compte supprimé avec succès',
        'has_lower_case' => 'Contient une minuscule',
        'has_upper_case' => 'Contient une majuscule',
        'has_special_char' => 'Contient un caractère spécial',
        'has_number' => 'Contient un chiffre',
        'is_long_enough' => 'Au moins 8 caractères',
        'mandatory_rules' => 'Obligatoire',
        'optional_rules_you_should_follow' => 'Recommandé (fortement)',
        'caps_lock_is_on' => 'Verr Maj est activé',
    ],

];
