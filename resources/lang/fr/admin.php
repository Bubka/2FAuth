<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'admin' => 'Admin',
    'admin_panel' => 'Administration',
    'app_setup' => 'Configuration',
    'auth' => 'Auth',
    'registrations' => 'Inscriptions',
    'users' => 'Utilisateurs',
    'users_legend' => 'Gérer les utilisateurs de votre instance ou créer de nouveaux utilisateurs.',
    'admin_settings' => 'Paramètres administrateur',
    'create_new_user' => 'Créer un utilisateur',
    'new_user' => 'Nouvel utilisateur',
    'search_user_placeholder' => 'nom d\'utilisateur, email ...',
    'quick_filters_colons' => 'Filtres rapides :',
    'user_created' => 'Utilisateur créé avec succès',
    'confirm' => [
        'delete_user' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur ? Il est impossible de restaurer un utilisateur supprimé.',
        'request_password_reset' => 'Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?',
        'purge_password_reset_request' => 'Êtes-vous sûr de vouloir supprimer la demande précédente ?',
        'delete_account' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?',
        'edit_own_account' => 'Il s\'agit de votre propre compte. Êtes-vous sûr ?',
        'change_admin_role' => 'Cela va grandement impacter les permissions de cet utilisateur. Êtes-vous sûr ?',
        'demote_own_account' => 'Vous ne serez plus administrateur. Vraiment sûr ?'
    ],
    'logs' => 'Logs',
    'administration_legend' => 'Les paramètres suivants sont généraux et s\'appliquent à tous les utilisateurs.',
    'user_management' => 'Gestion de l\'utilisateur',
    'oauth_provider' => 'Fournisseur OAuth',
    'account_bound_to_x_via_oauth' => 'Ce compte est lié à un compte :provider via OAuth',
    'last_seen_on_date' => 'Dernière connexion :date',
    'registered_on_date' => 'Inscription :date',
    'updated_on_date' => 'Dernière mise à jour :date',
    'access' => 'Accès',
    'password_requested_on_t' => 'Une demande de réinitialisation de mot de passe existe pour cet utilisateur (demande envoyée le :datetime). Cela signifie que l\'utilisateur n\'a pas encore changé son mot de passe, mais que le lien qu\'il a reçu est toujours valide. Cette demande peut venir de l\'utilisateur lui-même ou d\'un administrateur.',
    'password_request_expired' => 'Une demande de réinitialisation de mot de passe existe pour cet utilisateur mais a expiré. Cela signifie que l\'utilisateur n\'a pas changé son mot de passe à temps. Cette demande peut venir de l\'utilisateur lui-même ou d\'un administrateur.',
    'resend_email' => 'Renvoyer l’email',
    'resend_email_title' => 'Renvoyer un email de réinitialisation du mot de passe à l\'utilisateur',
    'resend_email_help' => 'Utilisez <b>Renvoyer l\'email</b> pour renvoyer un nouvel email de réinitialisation de mot de passe à l\'utilisateur afin qu\'il puisse choisir un nouveau mot de passe. Renvoyer un nouvel email ne change pas le mot de passe actuel de l\'utilisateur, toutes les demandes précédement envoyées seront révoquées.',
    'reset_password' => 'Réinitialiser le mdp',
    'reset_password_help' => 'Utilisez <b>Réinitialiser le mdp</b> pour forcer la réinitialisation du mot de passe immédiatement (cela va définir un mot de passe temporaire) avant d\'envoyer un e-mail de réinitialisation de mot de passe à l\'utilisateur. L\'utilisateur pourra alors choisir un nouveau mot de passe personnalisé. Les demandes précédement envoyées seront révoquées.',
    'reset_password_title' => 'Réinitialiser le mot de passe de l\'utilisateur',
    'password_successfully_reset' => 'Mot de passe réinitialisé avec succès',
    'user_has_x_active_pat' => ':count jeton(s) actif(s)',
    'user_has_x_security_devices' => ':count périphérique(s) de sécurité',
    'revoke_all_pat_for_user' => 'Révoquer tous les jetons de l\'utilisateur',
    'revoke_all_devices_for_user' => 'Révoquer tous les périphériques de sécurité de l\'utilisateur',
    'danger_zone' => 'Zone dangereuse',
    'delete_this_user_legend' => 'Le compte utilisateur sera supprimé ainsi que toutes ses données 2FA.',
    'this_is_not_soft_delete' => 'Il ne s\'agit pas d\'une désactivation, le compte ne sera pas récupérable.',
    'delete_this_user' => 'Supprimer l\'utilisateur',
    'user_role_updated' => 'Rôle utilisateur mis à jour',
    'pats_succesfully_revoked' => 'Jetons de l\'utilisateur révoqués avec succès',
    'security_devices_succesfully_revoked' => 'Périphériques de sécurité de l\'utilisateur révoqués avec succès',
    'variables' => 'Variables',
    'cache_cleared' => 'Cache vidé',
    'cache_optimized' => 'Cache optimisé',
    'check_now' => 'Vérifier maintenant',
    'view_on_github' => 'Voir sur GitHub',
    'x_is_available' => ':version est disponible',
    'successful_login_on' => 'Connexion réussie le <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Déconnexion réussie le <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Connexion refusée le <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Vu le <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Derniers accès',
    'see_full_log' => 'Voir le log complet',
    'browser_on_platform' => ':browser sur :platform',
    'access_log_has_more_entries' => 'Le journal d\'accès contient plus d\'entrées.',
    'access_log_legend_for_user' => 'Journal des accès de l\'utilisateur :username',
    'show_last_month_log' => 'Afficher les entrées sur un mois',
    'show_three_months_log' => 'Afficher les entrées sur 3 mois',
    'show_six_months_log' => 'Afficher les entrées sur 6 mois',
    'show_one_year_log' => 'Afficher les entrées sur un an',
    'sort_by_date_asc' => 'Afficher les plus anciens en premier',
    'sort_by_date_desc' => 'Afficher les plus récents en premier',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'database' => 'Base de données',
    'file_system' => 'Système de fichiers',
    'storage' => 'Stockage',
    'forms' => [
        'use_encryption' => [
            'label' => 'Protéger les données sensibles',
            'help' => 'Les données sensibles, les secrets et les e-mails 2FA, sont stockés chiffrés dans la base de données. Assurez-vous de sauvegarder la valeur APP_KEY de votre fichier env (ou tout le fichier) car il sert de clé de chiffrement. Il n\'y a aucun moyen de déchiffrer les données chiffrées sans cette clé.',
        ],
        'restrict_registration' => [
            'label' => 'Restreindre les inscriptions',
            'help' => 'Restreint la possibilité de s\'inscrire à certaines adresses email seulement. Les deux règles peuvent être utilisées simultanément. Cette limitation est sans effet sur les inscriptions via SSO.',
        ],
        'restrict_list' => [
            'label' => 'Listes de filtrage',
            'help' => 'Les adresses email dans cette liste seront autorisées à s\'inscrire. Séparez les adresses avec le caractère "|"',
        ],
        'restrict_rule' => [
            'label' => 'Règle de filtrage',
            'help' => 'Les adresses emails validant cette expression régulière seront autorisées à s\'inscrire',
        ],
        'disable_registration' => [
            'label' => 'Désactiver les inscriptions',
            'help' => 'Empêche l\'inscription de nouveaux utilisateurs. A moins que ce réglage ne soit surchargé (voir ci-après), cela affecte également l\'inscription (c\'est à dire la première connexion) via SSO',
        ],
        'enable_sso' => [
            'label' => 'Activer SSO',
            'help' => 'Permet aux visiteurs de s\'authentifier avec un compte externe grâce à la méthode Single Sign-On',
        ],
        'use_sso_only' => [
            'label' => 'Utiliser uniquement SSO',
            'help' => 'Définir SSO comme la seule méthode disponible pour se connecter à 2FAuth. La connexion par mot de passe ou Webauthn est alors désactivée pour les utilisateurs. Les administrateurs ne sont pas affectés par cette restriction.',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'Garder l\'inscription via SSO activée',
            'help' => 'Permet aux nouveaux utilisateurs de se connecter pour la première fois via SSO alors que les inscriptions sont désactivées',
        ],
        'is_admin' => [
            'label' => 'Est administrateur',
            'help' => 'Donne les droits d\'administrateur à l\'utilisateur. Les administrateurs peuvent gérer l\'application, c\'est à dire modifier ses paramètres et gérer ses utilisateurs. Un administrateur n\'a aucun moyen de consulter les données 2FA d\'un autre utilisateur ou de lui générer des codes.'
        ],
        'test_email' => [
            'label' => 'Test de la configuration Email',
            'help' => 'Envoyez un email de test pour contrôler la capacité de votre instance à utiliser l\'email. Il est important d\'avoir une configuration fonctionnelle, sans quoi les utilisateurs ne pourront pas demander de réinitialisation de leur mot de passe par exemple.',
            'email_will_be_send_to_x' => 'L\'email sera envoyé à <span class="is-family-code has-text-info">:email</span>',
        ],
        'health_endpoint' => [
            'label' => 'État de santé',
            'help' => 'URL que vous pouvez visiter pour vérifier l\'état de santé de cette instance 2FAuth. Cette URL peut être utilisée pour configurer une sonde Docker HEALTHCHECK ou une sonde Kubernetes HTTPS Liveness.',
        ],
        'cache_management' => [
            'label' => 'Gestion du cache',
            'help' => 'Parfois le cache doit être effacé, par exemple après une modification de variable d\'environnement ou une mise à jour. Vous pouvez le faire ici.',
        ],
        'store_icon_to_database' => [
            'label' => 'Enregistrer les icônes dans la base de données',
            'help' => 'Les icônes sont enregistrées dans la base de données en plus de leur stockage dans le système de fichiers. Celui-ci est alors utilisé uniquement comme cache. Cela facilite la création de backup de votre instance 2FAuth car seule sa base de données est à sauvegarder.<br /><br />Attention toutefois, certains effets négatifs sont possibles : La taille de la base de données va augmenter significativement et rapidement si l\'instance héberge beaucoup d\'icônes de grandes résolutions. Cela peut également affecter les performances de l\'application car le système de fichiers est plus souvent intéroggé pour s\'assurer que son état reflète bien celui de la base de données.',
        ],
    ],

];