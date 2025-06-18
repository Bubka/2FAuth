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

    'resource_not_found' => 'Ressource introuvable',
    'error_occured' => 'Une erreur est survenue :',
    'refresh' => 'Actualiser',
    'no_valid_otp' => 'Aucune donnée OTP valide dans ce QR code',
    'something_wrong_with_server' => 'Il y a un problème avec votre serveur',
    'Unable_to_decrypt_uri' => 'uri impossible à décoder',
    'not_a_supported_otp_type' => 'Ce format OTP n\'est pas supporté pour le moment',
    'cannot_create_otp_without_secret' => 'Impossible de créer un OTP sans un secret',
    'data_of_qrcode_is_not_valid_URI' => 'Les données de ce code QR ne forment pas une URI OTP Auth valide. Le QR code contient :',
    'wrong_current_password' => 'Mot de passe actuel érroné, rien n\a été modifié',
    'error_during_encryption' => 'Le chiffrement a échoué, votre base de données reste non protégée.',
    'error_during_decryption' => 'Le déchiffrement a échoué, votre base de données est toujours protégée. Ceci est principalement dû à un problème d\'intégrité des données chiffrées pour un ou plusieurs comptes.',
    'qrcode_cannot_be_read' => 'Ce QR code est illisible',
    'too_many_ids' => 'trop d\'IDs ont été inclus dans le paramètre de requête, max. 100 autorisés',
    'delete_user_setting_only' => 'Seuls les paramètres créés par l\'utilisateur peuvent être supprimés',
    'indecipherable' => '*indéchiffrable*',
    'cannot_decipher_secret' => 'Le secret ne peut pas être déchiffré. Ceci est généralement causé par une valeur APP_KEY incorrecte définie dans le fichier de configuration .env de 2Fauth ou des données corrompues dans la base de données.',
    'https_required' => 'Contexte HTTPS requis',
    'browser_does_not_support_webauthn' => 'Votre appareil ne supporte pas WebAuthn. Réessayez plus tard en utilisant un navigateur plus récent',
    'aborted_by_user' => 'Abandonné par l\'utilisateur',
    'security_device_already_registered' => 'Périphérique déjà enregistré',
    'not_allowed_operation' => 'Opération non autorisée',
    'no_authenticator_support_specified_algorithms' => 'Aucun authentificateur ne supporte les algorithmes spécifiés',
    'authenticator_missing_discoverable_credential_support' => 'Identifiants découvrables non supportés par l\'authentificateur',
    'authenticator_missing_user_verification_support' => 'Vérification de l\'utilisateur non supportée par l\'authentificateur',
    'unknown_error' => 'Erreur inconnue',
    'security_error_check_rpid' => 'Erreur de sécurité<br/>Vérifiez votre variable d\'environnement WEBAUTHN_ID',
    '2fauth_has_not_a_valid_domain' => 'Le domaine de l\'instance 2FAuth n\'est pas un domaine valide',
    'user_id_not_between_1_64' => 'L\'identifiant utilisateur n\'est pas compris entre 1 et 64 caractères',
    'no_entry_was_of_type_public_key' => 'Aucune entrée de type "public-key"',
    'unsupported_with_reverseproxy' => 'Sans effet avec un proxy d\'authentification ou SSO',
    'unsupported_with_sso_only' => 'Cette méthode d\'authentification est réservée aux administrateurs. Les utilisateurs doivent se connecter avec SSO.',
    'user_deletion_failed' => 'La suppression du compte utilisateur a échoué, aucune donnée n\'a été supprimée',
    'auth_proxy_failed' => 'Échec de l\'authentification par le proxy',
    'auth_proxy_failed_legend' => '2Fauth est configuré pour fonctionner derrière un proxy d\'authentification, mais votre proxy ne retourne pas l\'en-tête attendu. Vérifiez votre configuration et réessayez.',
    'invalid_x_migration' => 'Données :appname invalides ou illisibles',
    'invalid_2fa_data' => 'Données 2FA invalides',
    'unsupported_migration' => 'Les données ne correspondent à aucun format pris en charge',
    'unsupported_otp_type' => 'Type OTP non supporté',
    'encrypted_migration' => 'Non lisible, les données semblent chiffrées',
    'no_icon_for_this_variant' => 'Aucune icône disponible dans cette variante',
    'file_upload_failed' => 'Échec de téléchargement du fichier',
    'unauthorized' => 'Non autorisé',
    'unauthorized_legend' => 'Vous n\'avez pas la permission de voir cette ressource ou d\'effectuer cette action',
    'cannot_delete_the_only_admin' => 'Impossible de supprimer le seul compte administrateur',
    'cannot_demote_the_only_admin' => 'Impossible de rétrograder le seul compte administrateur',
    'error_during_data_fetching' => '💀 Une erreur s\'est produite lors de la récupération des données',
    'check_failed_try_later' => 'Échec de la vérification, veuillez réessayer plus tard',
    'sso_disabled' => 'SSO est désactivé',
    'sso_bad_provider_setup' => 'Ce fournisseur SSO n\'est pas entièrement configuré dans votre fichier .env',
    'sso_failed' => 'Authentification via SSO refusée',
    'sso_no_register' => 'Les inscriptions sont désactivées',
    'sso_email_already_used' => 'Un compte utilisateur avec la même adresse e-mail existe déjà mais ne correspond pas à votre compte externe. N\'utilisez pas SSO si vous êtes déjà inscrit sur 2FAuth avec cette adresse e-mail.',
    'account_managed_by_external_provider' => 'Compte géré par un fournisseur externe',
    'data_cannot_be_refreshed_from_server' => 'Les données ne peuvent être actualisées depuis le serveur',
    'no_pwd_reset_for_this_user_type' => 'Réinitialisation du mot de passe indisponible pour cet utilisateur',
    'cannot_detect_qrcode_in_image' => 'Impossible de détecter un QR code dans l\'image, essayez de recadrer l\'image',
    'cannot_decode_detected_qrcode' => 'Impossible de décoder le QR code détecté, essayez de rogner ou d\'améliorer l\'image',
    'qrcode_has_invalid_checksum' => 'La somme de contrôle du QR code est invalide',
    'no_readable_qrcode' => 'Aucun QR code lisible',
    'failed_icon_store_database_toggling' => 'La migration des icônes a échoué. Le paramètre a été restaurée à sa précédente valeur.',
    'failed_to_retrieve_app_settings' => 'Impossible de récupérer les paramètres de l\'application',
    'reserved_name_please_choose_something_else' => 'Nom réservé, veuillez en choisir un autre',
];