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
    'already_one_user_registered' => 'Un compte utilisateur existe déjà.',
    'cannot_register_more_user' => 'Vous ne pouvez pas enregistrer plus d\'un utilisateur.',
    'refresh' => 'Actualiser',
    'no_valid_otp' => 'Aucune donnée OTP valide dans ce QR code',
    'something_wrong_with_server' => 'Il y a un problème avec votre serveur',
    'Unable_to_decrypt_uri' => 'uri impossible à décoder',
    'not_a_supported_otp_type' => 'Ce format OTP n\'est pas supporté pour le moment',
    'cannot_create_otp_without_secret' => 'Impossible de créer un OTP sans un secret',
    'cannot_create_otp_with_those_parameters' => 'Impossible de créer un OTP avec ces paramètres',
    'wrong_current_password' => 'Mot de passe actuel érroné, rien n\a été modifié',
    'error_during_encryption' => 'Le chiffrement a échoué, votre base de données reste non protégée.',
    'error_during_decryption' => 'Le déchiffrement a échoué, votre base de données est toujours protégée. Ceci est principalement dû à un problème d\'intégrité des données chiffrées pour un ou plusieurs comptes.',
    'qrcode_cannot_be_read' => 'Ce QR code est illisible',
];