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
    'throttle' => 'Tentatives de connexion trop nombreuses. Veuillez essayer de nouveau dans :seconds secondes.',

    // 2FAuth
    'sign_out' => 'Déconnexion',
    'sign_in' => 'Se connecter',
    'register' => 'Créer un compte',
    'welcome_back_x' => 'Bienvenue {0}',
    'already_authenticated' => 'Déjà authentifié',
    'confirm' => [
        'logout' => 'Etes-vous sûrs de vouloir vous déconnecter ?',
    ],
    'forms' => [
        'name' => 'Nom',
        'login' => 'Connexion',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'confirm_password' => 'Confirmez le mot de passe',
        'confirm_new_password' => 'Confirmez le nouveau mot de passe',
        'dont_have_account_yet' => 'Pas encore de compte ?',
        'already_register' => 'Déjà enregistré ?',
        'password_do_not_match' => 'Le mot de passe ne correspond pas',
        'forgot_your_password' => 'Mot de passe oublié ?',
        'request_password_reset' => 'Réinitialisez-le',
        'reset_password' => 'Mot de passe oublié',
        'no_reset_password_in_demo' => 'Réinitialisation impossible en mode Démo',
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
        'welcome_to_demo_app_use_those_credentials' => 'bienvenue sur la démo de 2FAuth.<br><br>Vous pouvez vous connecter en utilisant l\'adresse email <strong>demo@2fauth.app</strong> et le mot de passe <strong>demo</demo>',
        'register_punchline' => 'Bienvenue sur 2FAuth.<br/>Vous avez besoin d\'un compte pour aller plus loin. Remplissez ce formulaire pour vous inscrire et, s\'il vous plaît, choisissez un mot de passe fort, les données 2FA sont sensibles.',
        'reset_punchline' => '2FAuth vous enverra un lien de réinitialisation de mot de passe à cette adresse. Cliquez sur le lien dans l\'e-mail reçu pour définir un nouveau mot de passe.',
    ],

];
