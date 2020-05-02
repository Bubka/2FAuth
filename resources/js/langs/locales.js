export default {
    "en": {
        "auth": {
            "sign_out": "Sign out",
            "sign_in": "Sign in",
            "register": "Register",
            "hello": "Hi {username} !",
            "throttle": "Too many login attempts. Please try again in {seconds} seconds.",
            "already_authenticated": "Already authenticated",
            "confirm": {
                "logout": "Are you sure you want to log out?"
            },
            "forms": {
                "name": "Name",
                "login": "Login",
                "email": "Email",
                "password": "Password",
                "confirm_password": "Confirm password",
                "confirm_new_password": "Confirm new password",
                "dont_have_account_yet": "Don't have your account yet?",
                "already_register": "Already registered?",
                "password_do_not_match": "Password do not match",
                "forgot_your_password": "Forgot your password?",
                "request_password_reset": "Request a password reset",
                "reset_password": "Reset password",
                "new_password": "New password",
                "current_password": {
                    "label": "Current password",
                    "help": "Fill in your current password to confirm that it's you"
                },
                "change_password": "Change password",
                "send_password_reset_link": "Send password reset link",
                "change_your_password": "Change your password",
                "password_successfully_changed": "Password successfully changed ",
                "edit_account": "Edit account",
                "profile_saved": "Profile successfully updated!",
                "welcome_to_demo_app_use_those_credentials": "Welcome to the 2FAuth demo.<br><br>You can connect using the email address <strong>demo@2fauth.app</strong> and the password <strong>demo</demo>"
            }
        },
        "commons": {
            "cancel": "Cancel",
            "update": "Update",
            "copy_to_clipboard": "Copy to clipboard",
            "profile": "Profile",
            "edit": "Edit",
            "delete": "Delete",
            "save": "Save",
            "close": "Close",
            "demo_do_not_post_sensitive_data": "This is a demo app, do not post any sensitive data"
        },
        "errors": {
            "resource_not_found": "Resource not found",
            "error_occured": "An error occured:",
            "already_one_user_registered": "There is already a registered user.",
            "cannot_register_more_user": "You cannot register more than one user.",
            "refresh": "refresh",
            "please": "Please ",
            "response": {
                "no_valid_otp": "No valid OTP resource in this QR code"
            },
            "something_wrong_with_server": "Something is wrong with your server",
            "Unable_to_decrypt_uri": "Unable to decrypt uri",
            "wrong_current_password": "Wrong current password, nothing has changed"
        },
        "languages": {
            "en": "English",
            "fr": "French"
        },
        "pagination": {
            "previous": "&laquo; Previous",
            "next": "Next &raquo;"
        },
        "passwords": {
            "password": "Passwords must be at least eight characters and match the confirmation.",
            "reset": "Your password has been reset!",
            "sent": "We have e-mailed your password reset link!",
            "token": "This password reset token is invalid.",
            "user": "We can't find a user with that e-mail address."
        },
        "settings": {
            "settings": "Settings",
            "account": "Account",
            "password": "Password",
            "options": "Options",
            "confirm": [],
            "forms": {
                "edit_settings": "Edit settings",
                "setting_saved": "Settings saved",
                "language": {
                    "label": "Language",
                    "help": "Change the language used to translate the app interface."
                },
                "show_token_as_dot": {
                    "label": "Show generated tokens as dot",
                    "help": "Replace generated token caracters with *** to ensure confidentiality. Do not affect the copy/paste feature."
                },
                "close_token_on_copy": {
                    "label": "Close token after copy",
                    "help": "Automatically close the popup showing the generated token after it has been copied"
                },
                "use_basic_qrcode_reader": {
                    "label": "Use basic qrcode reader",
                    "help": "If you experiences issues when capturing qrCodes enables this option to switch to a more basic but more reliable qrcode reader"
                },
                "desktop_display_mode": {
                    "label": "Desktop display mode",
                    "help": "Choose whether you want accounts to be displayed as a list or as a grid on desktop"
                },
                "grid": "Grid",
                "list": "List"
            }
        },
        "twofaccounts": {
            "service": "Service",
            "account": "Account",
            "icon": "Icon",
            "new": "New",
            "no_account_here": "No 2FA here!",
            "add_first_account": "Add your first account",
            "use_full_form": "Or use the full form",
            "add_one": "Add one",
            "manage": "Manage",
            "done": "Done",
            "forms": {
                "service": {
                    "placeholder": "example.com"
                },
                "account": {
                    "placeholder": "John DOE"
                },
                "new_account": "New account",
                "edit_account": "Edit account",
                "otp_uri": "OTP Uri",
                "hotp_counter": "HOTP Counter",
                "scan_qrcode": "Scan a qrcode",
                "use_qrcode": {
                    "val": "Use a qrcode",
                    "title": "Use a QR code to fill the form magically"
                },
                "unlock": {
                    "val": "Unlock",
                    "title": "Unlock it (at your own risk)"
                },
                "lock": {
                    "val": "Lock",
                    "title": "Lock it"
                },
                "choose_image": "Choose an image…",
                "create": "Create",
                "save": "Save",
                "test": "Test"
            },
            "stream": {
                "need_grant_permission": "You need to grant camera access permission",
                "not_readable": "Fail to load scanner. Is the camera already in use?",
                "no_cam_on_device": "No camera on this device",
                "secured_context_required": "Secure context required (HTTPS or localhost)",
                "camera_not_suitable": "Installed cameras are not suitable",
                "stream_api_not_supported": "Stream API is not supported in this browser"
            },
            "confirm": {
                "delete": "Are you sure you want to delete this account?",
                "cancel": "The account will be lost. Are you sure?"
            }
        },
        "validation": {
            "accepted": "The {attribute} must be accepted.",
            "active_url": "The {attribute} is not a valid URL.",
            "after": "The {attribute} must be a date after {date}.",
            "after_or_equal": "The {attribute} must be a date after or equal to {date}.",
            "alpha": "The {attribute} may only contain letters.",
            "alpha_dash": "The {attribute} may only contain letters, numbers, dashes and underscores.",
            "alpha_num": "The {attribute} may only contain letters and numbers.",
            "array": "The {attribute} must be an array.",
            "before": "The {attribute} must be a date before {date}.",
            "before_or_equal": "The {attribute} must be a date before or equal to {date}.",
            "between": {
                "numeric": "The {attribute} must be between {min} and {max}.",
                "file": "The {attribute} must be between {min} and {max} kilobytes.",
                "string": "The {attribute} must be between {min} and {max} characters.",
                "array": "The {attribute} must have between {min} and {max} items."
            },
            "boolean": "The {attribute} field must be true or false.",
            "confirmed": "The {attribute} confirmation does not match.",
            "date": "The {attribute} is not a valid date.",
            "date_equals": "The {attribute} must be a date equal to {date}.",
            "date_format": "The {attribute} does not match the format {format}.",
            "different": "The {attribute} and {other} must be different.",
            "digits": "The {attribute} must be {digits} digits.",
            "digits_between": "The {attribute} must be between {min} and {max} digits.",
            "dimensions": "The {attribute} has invalid image dimensions.",
            "distinct": "The {attribute} field has a duplicate value.",
            "email": "The {attribute} must be a valid email address.",
            "ends_with": "The {attribute} must end with one of the following: {values}",
            "exists": "The selected {attribute} is invalid.",
            "file": "The {attribute} must be a file.",
            "filled": "The {attribute} field must have a value.",
            "gt": {
                "numeric": "The {attribute} must be greater than {value}.",
                "file": "The {attribute} must be greater than {value} kilobytes.",
                "string": "The {attribute} must be greater than {value} characters.",
                "array": "The {attribute} must have more than {value} items."
            },
            "gte": {
                "numeric": "The {attribute} must be greater than or equal {value}.",
                "file": "The {attribute} must be greater than or equal {value} kilobytes.",
                "string": "The {attribute} must be greater than or equal {value} characters.",
                "array": "The {attribute} must have {value} items or more."
            },
            "image": "The {attribute} must be an image.",
            "in": "The selected {attribute} is invalid.",
            "in_array": "The {attribute} field does not exist in {other}.",
            "integer": "The {attribute} must be an integer.",
            "ip": "The {attribute} must be a valid IP address.",
            "ipv4": "The {attribute} must be a valid IPv4 address.",
            "ipv6": "The {attribute} must be a valid IPv6 address.",
            "json": "The {attribute} must be a valid JSON string.",
            "lt": {
                "numeric": "The {attribute} must be less than {value}.",
                "file": "The {attribute} must be less than {value} kilobytes.",
                "string": "The {attribute} must be less than {value} characters.",
                "array": "The {attribute} must have less than {value} items."
            },
            "lte": {
                "numeric": "The {attribute} must be less than or equal {value}.",
                "file": "The {attribute} must be less than or equal {value} kilobytes.",
                "string": "The {attribute} must be less than or equal {value} characters.",
                "array": "The {attribute} must not have more than {value} items."
            },
            "max": {
                "numeric": "The {attribute} may not be greater than {max}.",
                "file": "The {attribute} may not be greater than {max} kilobytes.",
                "string": "The {attribute} may not be greater than {max} characters.",
                "array": "The {attribute} may not have more than {max} items."
            },
            "mimes": "The {attribute} must be a file of type: {values}.",
            "mimetypes": "The {attribute} must be a file of type: {values}.",
            "min": {
                "numeric": "The {attribute} must be at least {min}.",
                "file": "The {attribute} must be at least {min} kilobytes.",
                "string": "The {attribute} must be at least {min} characters.",
                "array": "The {attribute} must have at least {min} items."
            },
            "not_in": "The selected {attribute} is invalid.",
            "not_regex": "The {attribute} format is invalid.",
            "numeric": "The {attribute} must be a number.",
            "present": "The {attribute} field must be present.",
            "regex": "The {attribute} format is invalid.",
            "required": "The {attribute} field is required.",
            "required_if": "The {attribute} field is required when {other} is {value}.",
            "required_unless": "The {attribute} field is required unless {other} is in {values}.",
            "required_with": "The {attribute} field is required when {values} is present.",
            "required_with_all": "The {attribute} field is required when {values} are present.",
            "required_without": "The {attribute} field is required when {values} is not present.",
            "required_without_all": "The {attribute} field is required when none of {values} are present.",
            "same": "The {attribute} and {other} must match.",
            "size": {
                "numeric": "The {attribute} must be {size}.",
                "file": "The {attribute} must be {size} kilobytes.",
                "string": "The {attribute} must be {size} characters.",
                "array": "The {attribute} must contain {size} items."
            },
            "starts_with": "The {attribute} must start with one of the following: {values}",
            "string": "The {attribute} must be a string.",
            "timezone": "The {attribute} must be a valid zone.",
            "unique": "The {attribute} has already been taken.",
            "uploaded": "The {attribute} failed to upload.",
            "url": "The {attribute} format is invalid.",
            "uuid": "The {attribute} must be a valid UUID.",
            "custom": {
                "attribute-name": {
                    "rule-name": "custom-message"
                },
                "icon": {
                    "image": "Supported format are jpeg, png, bmp, gif, svg, or webp"
                },
                "qrcode": {
                    "image": "Supported format are jpeg, png, bmp, gif, svg, or webp"
                },
                "uri": {
                    "starts_with": "Only valid OTP uri are supported"
                },
                "email": {
                    "exists": "No account found using this email"
                }
            },
            "attributes": []
        }
    },
    "fr": {
        "auth": {
            "sign_out": "Déconnexion",
            "sign_in": "Se connecter",
            "register": "Créer un compte",
            "hello": "Hi {username} !",
            "throttle": "Trop de tentatives de connexion. Veuillez réessayer dans {seconds} secondes.",
            "already_authenticated": "Déjà authentifié",
            "confirm": {
                "logout": "Etes-vous sûrs de vouloir vous déconnecter ?"
            },
            "forms": {
                "name": "Nom",
                "login": "Connexion",
                "email": "Email",
                "password": "Mot de passe",
                "confirm_password": "Confirmez le mot de passe",
                "confirm_new_password": "Confirmez le nouveau mot de passe",
                "dont_have_account_yet": "Pas encore de compte ?",
                "already_register": "Déjà enregistré ?",
                "password_do_not_match": "Le mot de passe ne correspond pas",
                "forgot_your_password": "Mot de passe oublié ?",
                "request_password_reset": "Réinitialiser le mot de passe",
                "reset_password": "Mot de passe oublié",
                "new_password": "Nouveau mot de passe",
                "current_password": {
                    "label": "Mot de passe actuel",
                    "help": "Indiquez votre mot de passe actuel pour confirmer qu'il s'agit bien de vous"
                },
                "change_password": "Modifier le mot de passe",
                "send_password_reset_link": "Envoyer",
                "change_your_password": "Modifier votre mot de passe",
                "password_successfully_changed": "Mot de passe modifié avec succès",
                "edit_account": "Mis à jour du profil",
                "profile_saved": "Profil mis à jour avec succès !",
                "welcome_to_demo_app_use_those_credentials": "bienvenue sur la démo de 2FAuth.<br><br>Vous pouvez vous connecter en utilisant l'adresse email <strong>demo@2fauth.app</strong> et le mot de passe <strong>demo</demo>"
            }
        },
        "commons": {
            "cancel": "Annuler",
            "update": "Mettre à jour",
            "copy_to_clipboard": "Copier",
            "profile": "Profil",
            "edit": "Modifier",
            "delete": "Supprimer",
            "save": "Enregistrer",
            "close": "Fermer",
            "demo_do_not_post_sensitive_data": "Site de démonstration, ne postez aucune donnée sensible"
        },
        "errors": {
            "resource_not_found": "Ressource introuvable",
            "error_occured": "Une erreur est survenue :",
            "already_one_user_registered": "Un compte utilisateur existe déjà.",
            "cannot_register_more_user": "Vous ne pouvez pas enregistrer plus d'un utilisateur.",
            "refresh": "Actualiser",
            "please": "",
            "response": {
                "no_valid_otp": "Aucune donnée OTP valide dans ce QR code"
            },
            "something_wrong_with_server": "Il y a un problème avec votre serveur",
            "Unable_to_decrypt_uri": "uri impossible à décoder",
            "wrong_current_password": "Mot de passe actuel érroné, rien n\\a été modifié"
        },
        "languages": {
            "en": "Anglais",
            "fr": "Français"
        },
        "pagination": {
            "previous": "&laquo; Précédent",
            "next": "Suivant &raquo;"
        },
        "passwords": {
            "password": "Les mots de passe doivent contenir au moins huit caractères et être identiques.",
            "reset": "Votre mot de passe a été réinitialisé !",
            "sent": "Le lien pour réinitialiser votre mot de passe vient d'être envoyé !",
            "token": "Ce jeton de réinitialisation n'est pas valide.",
            "user": "Cette adresse email n'est pas celle de votre compte."
        },
        "settings": {
            "settings": "Réglages",
            "account": "Compte",
            "password": "Mot de passe",
            "options": "Options",
            "confirm": [],
            "forms": {
                "edit_settings": "Modifier les réglages",
                "setting_saved": "Réglages sauvegardés",
                "language": {
                    "label": "Langue",
                    "help": "Traduit l'application dans la langue choisie"
                },
                "show_token_as_dot": {
                    "label": "Rendre illisibles les codes générés",
                    "help": "Remplace les caractères des codes générés par des ●●● pour garantir leur confidentialité. N'affecte pas la fonction de copier/coller qui reste utilisable."
                },
                "close_token_on_copy": {
                    "label": "Ne plus afficher les codes copiés",
                    "help": "Ferme automatiquement le popup affichant le code généré dès que ce dernier a été copié."
                },
                "use_basic_qrcode_reader": {
                    "label": "Utiliser le lecteur de qrcode basique",
                    "help": "Si vous rencontrez des problèmes lors de la lecture des qrCodes activez cette option pour utiliser un lecteur de qrcode moins évolué mais plus largement compatible"
                },
                "desktop_display_mode": {
                    "label": "Mode d'affichage Desktop",
                    "help": "Change la représentation des comptes, soit sous forme de liste, soit sous forme de grille"
                },
                "grid": "Grille",
                "list": "Liste"
            }
        },
        "twofaccounts": {
            "service": "Service",
            "account": "Compte",
            "icon": "Icône",
            "new": "Nouveau",
            "no_account_here": "Aucun compte 2FA !",
            "add_first_account": "Ajouter votre premier compte",
            "use_full_form": "Ou utiliser le formulaire détaillé",
            "add_one": "Add one",
            "manage": "Gérer",
            "done": "Terminé",
            "forms": {
                "service": {
                    "placeholder": "example.com"
                },
                "account": {
                    "placeholder": "Marc Dupont"
                },
                "new_account": "Nouveau compte",
                "edit_account": "Modifier le compte",
                "otp_uri": "OTP Uri",
                "hotp_counter": "Compteur HOTP",
                "scan_qrcode": "Scanner un QR code",
                "use_qrcode": {
                    "val": "Utiliser un QR code",
                    "title": "Utiliser un QR code pour renseigner le formulaire d'un seul coup d'un seul"
                },
                "unlock": {
                    "val": "Déverouiller",
                    "title": "Déverouiller le champ (à vos risques et périls)"
                },
                "lock": {
                    "val": "Vérouiller",
                    "title": "Vérouiller le champ"
                },
                "choose_image": "Choisir une image…",
                "create": "Créer",
                "save": "Enregistrer",
                "test": "Tester"
            },
            "stream": {
                "need_grant_permission": "Vous devez autoriser l'utilisation de votre caméra",
                "not_readable": "Le scanner ne se charge pas. La caméra est-elle déjà utilisée ?",
                "no_cam_on_device": "Votre équipement ne dispose pas de caméra",
                "secured_context_required": "Contexte sécurisé requis (HTTPS ou localhost)",
                "camera_not_suitable": "Votre équipement ne dispose pas d'une caméra adaptée",
                "stream_api_not_supported": "L'API Stream n'est pas supportée par votre navigateur"
            },
            "confirm": {
                "delete": "Etes-vous sûrs de vouloir supprimer le compte ?",
                "cancel": "Les données seront perdues, êtes-vous sûrs ?"
            }
        },
        "validation": {
            "accepted": "Le champ {attribute} doit être accepté.",
            "active_url": "Le champ {attribute} n'est pas une URL valide.",
            "after": "Le champ {attribute} doit être une date postérieure au {date}.",
            "after_or_equal": "Le champ {attribute} doit être une date postérieure ou égale au {date}.",
            "alpha": "Le champ {attribute} doit contenir uniquement des lettres.",
            "alpha_dash": "Le champ {attribute} doit contenir uniquement des lettres, des chiffres et des tirets.",
            "alpha_num": "Le champ {attribute} doit contenir uniquement des chiffres et des lettres.",
            "array": "Le champ {attribute} doit être un tableau.",
            "before": "Le champ {attribute} doit être une date antérieure au {date}.",
            "before_or_equal": "Le champ {attribute} doit être une date antérieure ou égale au {date}.",
            "between": {
                "numeric": "La valeur de {attribute} doit être comprise entre {min} et {max}.",
                "file": "La taille du fichier de {attribute} doit être comprise entre {min} et {max} kilo-octets.",
                "string": "Le texte {attribute} doit contenir entre {min} et {max} caractères.",
                "array": "Le tableau {attribute} doit contenir entre {min} et {max} éléments."
            },
            "boolean": "Le champ {attribute} doit être vrai ou faux.",
            "confirmed": "Le champ de confirmation {attribute} ne correspond pas.",
            "date": "Le champ {attribute} n'est pas une date valide.",
            "date_equals": "Le champ {attribute} doit être une date égale à {date}.",
            "date_format": "Le champ {attribute} ne correspond pas au format {format}.",
            "different": "Les champs {attribute} et {other} doivent être différents.",
            "digits": "Le champ {attribute} doit contenir {digits} chiffres.",
            "digits_between": "Le champ {attribute} doit contenir entre {min} et {max} chiffres.",
            "dimensions": "La taille de l'image {attribute} n'est pas conforme.",
            "distinct": "Le champ {attribute} a une valeur en double.",
            "email": "Le champ {attribute} doit être une adresse email valide.",
            "ends_with": "Le champ {attribute} doit se terminer par une des valeurs suivantes : {values}",
            "exists": "Le champ {attribute} sélectionné est invalide.",
            "file": "Le champ {attribute} doit être un fichier.",
            "filled": "Le champ {attribute} doit avoir une valeur.",
            "gt": {
                "numeric": "La valeur de {attribute} doit être supérieure à {value}.",
                "file": "La taille du fichier de {attribute} doit être supérieure à {value} kilo-octets.",
                "string": "Le texte {attribute} doit contenir plus de {value} caractères.",
                "array": "Le tableau {attribute} doit contenir plus de {value} éléments."
            },
            "gte": {
                "numeric": "La valeur de {attribute} doit être supérieure ou égale à {value}.",
                "file": "La taille du fichier de {attribute} doit être supérieure ou égale à {value} kilo-octets.",
                "string": "Le texte {attribute} doit contenir au moins {value} caractères.",
                "array": "Le tableau {attribute} doit contenir au moins {value} éléments."
            },
            "image": "Le champ {attribute} doit être une image.",
            "in": "Le champ {attribute} est invalide.",
            "in_array": "Le champ {attribute} n'existe pas dans {other}.",
            "integer": "Le champ {attribute} doit être un entier.",
            "ip": "Le champ {attribute} doit être une adresse IP valide.",
            "ipv4": "Le champ {attribute} doit être une adresse IPv4 valide.",
            "ipv6": "Le champ {attribute} doit être une adresse IPv6 valide.",
            "json": "Le champ {attribute} doit être un document JSON valide.",
            "lt": {
                "numeric": "La valeur de {attribute} doit être inférieure à {value}.",
                "file": "La taille du fichier de {attribute} doit être inférieure à {value} kilo-octets.",
                "string": "Le texte {attribute} doit contenir moins de {value} caractères.",
                "array": "Le tableau {attribute} doit contenir moins de {value} éléments."
            },
            "lte": {
                "numeric": "La valeur de {attribute} doit être inférieure ou égale à {value}.",
                "file": "La taille du fichier de {attribute} doit être inférieure ou égale à {value} kilo-octets.",
                "string": "Le texte {attribute} doit contenir au plus {value} caractères.",
                "array": "Le tableau {attribute} doit contenir au plus {value} éléments."
            },
            "max": {
                "numeric": "La valeur de {attribute} ne peut être supérieure à {max}.",
                "file": "La taille du fichier de {attribute} ne peut pas dépasser {max} kilo-octets.",
                "string": "Le texte de {attribute} ne peut contenir plus de {max} caractères.",
                "array": "Le tableau {attribute} ne peut contenir plus de {max} éléments."
            },
            "mimes": "Le champ {attribute} doit être un fichier de type : {values}.",
            "mimetypes": "Le champ {attribute} doit être un fichier de type : {values}.",
            "min": {
                "numeric": "La valeur de {attribute} doit être supérieure ou égale à {min}.",
                "file": "La taille du fichier de {attribute} doit être supérieure à {min} kilo-octets.",
                "string": "Le texte {attribute} doit contenir au moins {min} caractères.",
                "array": "Le tableau {attribute} doit contenir au moins {min} éléments."
            },
            "not_in": "Le champ {attribute} sélectionné n'est pas valide.",
            "not_regex": "Le format du champ {attribute} n'est pas valide.",
            "numeric": "Le champ {attribute} doit contenir un nombre.",
            "password": "Le mot de passe est incorrect",
            "present": "Le champ {attribute} doit être présent.",
            "regex": "Le format du champ {attribute} est invalide.",
            "required": "Le champ {attribute} est obligatoire.",
            "required_if": "Le champ {attribute} est obligatoire quand la valeur de {other} est {value}.",
            "required_unless": "Le champ {attribute} est obligatoire sauf si {other} est {values}.",
            "required_with": "Le champ {attribute} est obligatoire quand {values} est présent.",
            "required_with_all": "Le champ {attribute} est obligatoire quand {values} sont présents.",
            "required_without": "Le champ {attribute} est obligatoire quand {values} n'est pas présent.",
            "required_without_all": "Le champ {attribute} est requis quand aucun de {values} n'est présent.",
            "same": "Les champs {attribute} et {other} doivent être identiques.",
            "size": {
                "numeric": "La valeur de {attribute} doit être {size}.",
                "file": "La taille du fichier de {attribute} doit être de {size} kilo-octets.",
                "string": "Le texte de {attribute} doit contenir {size} caractères.",
                "array": "Le tableau {attribute} doit contenir {size} éléments."
            },
            "starts_with": "Le champ {attribute} doit commencer avec une des valeurs suivantes : {values}",
            "string": "Le champ {attribute} doit être une chaîne de caractères.",
            "timezone": "Le champ {attribute} doit être un fuseau horaire valide.",
            "unique": "La valeur du champ {attribute} est déjà utilisée.",
            "uploaded": "Le fichier du champ {attribute} n'a pu être téléversé.",
            "url": "Le format de l'URL de {attribute} n'est pas valide.",
            "uuid": "Le champ {attribute} doit être un UUID valide",
            "custom": {
                "attribute-name": {
                    "rule-name": "custom-message"
                },
                "icon": {
                    "image": "Les formats acceptés sont jpeg, png, bmp, gif, svg, or webp"
                },
                "qrcode": {
                    "image": "Les formats acceptés sont jpeg, png, bmp, gif, svg, or webp"
                },
                "uri": {
                    "starts_with": "La valeur n'est pas une uri OTP valide"
                },
                "email": {
                    "exists": "Aucun compte utilisateur n'utilise cette email"
                }
            },
            "attributes": []
        }
    }
}
