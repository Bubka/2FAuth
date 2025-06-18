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
    'icon_to_illustrate_the_account' => 'Icône qui illustre le compte',
    'remove_icon' => 'Supprimer l\'icône',
    'no_account_here' => 'Aucun compte 2FA !',
    'add_first_account' => 'Choisissez une méthode et ajoutez votre premier compte',
    'use_full_form' => 'Ou utiliser le formulaire détaillé',
    'add_one' => 'Add one',
    'show_qrcode' => 'Afficher le QR code',
    'no_service' => '- aucun service -',
    'account_created' => 'Compte créé avec succès',
    'account_updated' => 'Compte mis à jour avec succès',
    'accounts_deleted' => 'Compte·s supprimé·s avec succès',
    'accounts_moved' => 'Compte·s déplacé·s avec succès',
    'export_selected_accounts' => 'Exporter les comptes sélectionnés',
    'twofauth_export_format' => 'Format 2FAuth',
    'twofauth_export_format_sub' => 'Exporter les comptes en utilisant le format json de 2FAuth',
    'twofauth_export_format_desc' => 'Utilisez cette option si vous avez besoin de créer une sauvegarde qui peut être restaurée. Ce format prend en charge les icônes.',
    'twofauth_export_format_url' => 'Le schéma de données est décrit ici :',
    'twofauth_export_schema' => 'Schéma d\'export 2FAuth',
    'otpauth_export_format' => 'URIs otpauth',
    'otpauth_export_format_sub' => 'Exporter les données sous forme de liste d\'URIs otpauth',
    'otpauth_export_format_desc' => 'L\'URI otpauth est le format le plus couramment utilisé pour échanger des données 2FA, par exemple sous la forme d\'un code QR lorsque vous activez l\'authentification 2FA sur un site Web. Préférez ce format si vous souhaitez quitter 2FAuth pour une solution alternative.',
    'reveal' => 'révéler',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
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
        'choose_image' => 'Télécharger',
        'i_m_lucky' => 'J\'ai de la chance',
        'i_m_lucky_legend' => 'Le bouton "J\'ai de la chance" essaie d\'obtenir une icône standard à partir de la collection d\'icônes sélectionnée. Saisissez une valeur pour le champ Service qui est simple, vous augmenterez vos chances d\'obtenir une icône : N\'ajoutez pas extension comme ".com", utilisez le nom exact du service, évitez les caractères spéciaux.',
        'test' => 'Tester',
        'group' => [
            'label' => 'Groupe',
            'help' => 'Le groupe auquel assigner le compte'
        ],
        'secret' => [
            'label' => 'Secret',
            'help' => 'La clé utilisée pour générer vos codes de sécurité'
        ],
        'plain_text' => 'Texte brut',
        'otp_type' => [
            'label' => 'Choisissez le type de code OTP à créer',
            'help' => 'Time-based OTP ou HMAC-based OTP ou Steam OTP'
        ],
        'digits' => [
            'label' => 'Nombre de chiffres',
            'help' => 'Le nombre de chiffres des codes de sécurité générés'
        ],
        'algorithm' => [
            'label' => 'Algorithme',
            'help' => 'L\'algorithme utilisé pour sécuriser vos codes de sécurité'
        ],
        'period' => [
            'label' => 'Durée de validité',
            'placeholder' => '30 s par défaut',
            'help' => 'La durée de validité des codes de sécurité générés, en seconde'
        ],
        'counter' => [
            'label' => 'Compteur',
            'placeholder' => '0 par défaut',
            'help' => 'La valeur initiale du compteur',
            'help_lock' => 'Il est risqué de modifier le compteur car vous pouvez désynchroniser le compte avec le serveur de vérification du service. Utilisez l\'icône cadenas pour activer la modification, mais seulement si vous savez ce que vous faites'
        ],
        'image' => [
            'label' => 'Image',
            'placeholder' => 'http://...',
            'help' => 'L\'URL d\'une image externe à utiliser comme icône du compte'
        ],
        'options_help' => 'Vous pouvez laisser les options suivantes non renseignées si vous ne savez pas comment les définir. Les valeurs les plus couramment utilisées seront appliquées.',
        'alternative_methods' => 'Méthodes alternatives',
        'spaces_are_ignored' => 'Les espaces indésirables seront automatiquement supprimés'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Le scanner ne peut pas démarrer :(',
        'need_grant_permission' => [
            'reason' => '2FAuth n\'a pas la permission d\'accéder à votre caméra',
            'solution' => 'Vous devez autoriser l\'utilisation de l\'appareil photo de votre appareil. Si vous avez déjà refusé et que votre navigateur ne vous le demande plus, veuillez vous référer à la documentation du navigateur pour savoir comment accorder l’autorisation.',
            'click_camera_icon' => 'Cela se fait généralement en cliquant sur une icône représentant une caméra barrée, dans ou à côté de la barre d\'adresse du navigateur',
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
        'cancel' => 'Les données seront perdues, êtes-vous sûrs ?',
        'discard' => 'Êtes-vous sûrs de vouloir retirer ce compte ?',
        'discard_all' => 'Êtes-vous sûrs de vouloir retirer tous les comptes ?',
        'discard_duplicates' => 'Êtes-vous sûrs de vouloir retirer tous les doublons ?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Importer',
        'import_legend' => '2FAuth peut importer les données de diverses applications 2FA.<br />Utilisez la fonction d\'exportation de ces applications pour obtenir une ressource de migration (un code QR ou un fichier) et chargez-la via une de ces méthodes.',
        'import_legend_afterpart' => 'Utilisez la fonction d\'exportation de ces applications pour obtenir une ressource de migration, comme un code QR ou un fichier JSON, puis chargez-la ici.',
        'upload' => 'Upload',
        'scan' => 'Scan',
        'supported_formats_for_qrcode_upload' => 'Accepté : jpg, jpeg, png, bmp, gif, svg, ou webp',
        'supported_formats_for_file_upload' => 'Accepté : Texte brut, json, 2fas',
        'expected_format_for_direct_input' => 'Attendu : Une liste d\'URI au format otpauth, une par ligne',
        'supported_migration_formats' => 'Formats de migration supportés',
        'qr_code' => 'QR Code',
        'text_file' => 'Fichier texte',
        'direct_input' => 'Saisie directe',
        'plain_text' => 'Texte brut',
        'parsing_data' => 'Analyse des données...',
        'issuer' => 'Émetteur',
        'imported' => 'Importé',
        'failure' => 'Échec',
        'x_valid_accounts_found' => '{count} comptes valides trouvés',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'Les comptes 2FA suivants ont été trouvés dans la ressource de migration. Pour l\'instant aucun d\'entre eux n\'a été ajouté à 2FAuth.',
        'use_buttons_to_save_or_discard' => 'Utilisez les boutons disponibles pour les enregistrer de façon permanente dans votre collection 2FA ou les retirer.',
        'import_all' => 'Tout importer',
        'import_this_account' => 'Importer ce compte',
        'discard_all' => 'Tout retirer',
        'discard_duplicates' => 'Retirer les doublons',
        'discard_this_account' => 'Retirer ce compte',
        'generate_a_test_password' => 'Générer un code OTP de test',
        'possible_duplicate' => 'Un compte avec les mêmes informations existe déjà',
        'invalid_account' => '- compte non valide -',
        'invalid_service' => '- service non valide -',
        'do_not_set_password_or_encryption' => 'N\'activez PAS la protection par mot de passe ou le chiffrement lorsque vous exportez des données (depuis une application 2FA) que vous comptez importer dans 2FAuth.',
    ],

];