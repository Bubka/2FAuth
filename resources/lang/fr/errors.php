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
    'cannot_register_more_user' => 'Vous ne pouvez pas enregistrer plus d\'un utilisateur.',
    'refresh' => 'Actualiser',
    'no_valid_otp' => 'Aucune donnée OTP valide dans ce QR code',
    'something_wrong_with_server' => 'Il y a un problème avec votre serveur',
    'Unable_to_decrypt_uri' => 'uri impossible à décoder',
    'not_a_supported_otp_type' => 'Ce format OTP n\'est pas supporté pour le moment',
    'cannot_create_otp_without_secret' => 'Impossible de créer un OTP sans un secret',
    'data_of_qrcode_is_not_valid_URI' => 'Les données de ce code QR ne forment pas une URI OTP Auth valide :',
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
    'security_device_unsupported' => 'Périphérique de sécurité non pris en charge',
    'unsupported_with_reverseproxy' => 'Sans effet avec un proxy d\'authentification',
    'user_deletion_failed' => 'La suppression du compte utilisateur a échoué, aucune donnée n\'a été supprimée',
    'auth_proxy_failed' => 'Échec de l\'authentification par le proxy',
    'auth_proxy_failed_legend' => '2Fauth est configuré pour fonctionner derrière un proxy d\'authentification, mais votre proxy ne retourne pas l\'en-tête attendu. Vérifiez votre configuration et réessayez.',
    'invalid_google_auth_migration' => 'Données Google Authenticator invalides ou illisibles',
    'unsupported_otp_type' => 'Type OTP non supporté',
    'no_logo_found_for_x' => 'Aucun logo disponible pour {service}'
];