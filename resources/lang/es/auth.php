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
    'failed' => 'Estas credenciales no coinciden con nuestros registros.',
    'password' => 'La contraseña proporcionada es incorrecta.',
    'throttle' => 'Demasiados intentos de acceso. Por favor intente nuevamente en :seconds segundos.',

    // 2FAuth
    'sign_out' => 'Cerrar sesión',
    'sign_in' => 'Iniciar sesión',
    'sign_in_using' => 'Inicia sesión, usando',
    'sign_in_using_security_device' => 'Iniciar sesión usando un dispositivo de seguridad',
    'login_and_password' => 'usuario y contraseña',
    'register' => 'Registrarse',
    'welcome_to_2fauth' => 'Welcome to 2FAuth',
    'autolock_triggered' => 'Autobloqueo activado',
    'autolock_triggered_punchline' => 'El evento observado por el Auto Bloqueo ha sido activado. Has sido desconectado automáticamente.',
    'change_autolock_in_settings' => 'Puedes cambiar el comportamiento de la función Auto Bloqueo en la pestaña Configuración > Opciones.',
    'already_authenticated' => 'Ya está autenticado',
    'authentication' => 'Autenticación',
    'maybe_later' => 'Quizás más tarde',
    'user_account_controlled_by_proxy' => 'Cuenta de usuario disponible por un proxy de autenticación.<br />Administra la cuenta a nivel de proxy.',
    'auth_handled_by_proxy' => 'Autenticación administrada por un proxy inverso, las configuraciones de abajo están deshabilitadas.<br />Gestionar autenticación a nivel proxy.',
    'confirm' => [
        'logout' => '¿Seguro que quieres cerrar la sesión?',
        'revoke_device' => '¿Está seguro que quiere eliminar este dispositivo?',
        'delete_account' => '¿Está seguro que desea eliminar su cuenta?',
    ],
    'webauthn' => [
        'security_device' => 'un dispositivo de seguridad',
        'security_devices' => 'Dispositivos de seguridad',
        'security_devices_legend' => 'Dispositivos de autenticación que pudes usar para iniciar sesión en 2FAuth, como: llaves de seguridad (ej. Yubikey) o smartphones con capacidades biométricas (ej. Apple FaceID/TouchID)',
        'enhance_security_using_webauthn' => 'Puede mejorar la seguridad de su cuenta 2FAuth activando la autenticación WebAuthn.<br /><br />
            WebAuthn permite el uso de dispositivos de confianza (como, Yubikeys o smartphones con capacidades biométricas) para iniciar sesión rápidamente y de forma más segura.',
        'use_security_device_to_sign_in' => 'Prepárese para autenticarse usando (uno) de sus dispositivos de seguridad. Conecte su llave, retirar máscaras o guantes, etc.',
        'lost_your_device' => '¿Perdió su dispositivo?',
        'recover_your_account' => 'Recuperar su cuenta',
        'account_recovery' => 'Recuperación cuenta',
        'recovery_punchline' => '2FAuth will send you a recovery link to this email address. Click the link in the received email and follow the instructions.<br /><br />Ensure you open the email on a device you fully own.',
        'send_recovery_link' => 'Enviar enlace de recuperación',
        'account_recovery_email_sent' => '¡Correo de recuperación de cuenta enviado!',
        'disable_all_security_devices' => 'Disable all security devices',
        'disable_all_security_devices_help' => 'All your security devices will be revoked. Use this option if you have lost one or its security has been compromised.',
        'register_a_new_device' => 'Registrar un nuevo dispositivo',
        'register_a_device' => 'Registrar un dispositivo',
        'device_successfully_registered' => 'Dispositivo registrado correctamente',
        'device_revoked' => 'Dispositivo revocado correctamente',
        'revoking_a_device_is_permanent' => 'El revocado de un dispositivo es permanente',
        'recover_account_instructions' => 'To recover your account, 2FAuth resets some Webauthn settings so you will be able to sign in using your email and password.',
        'invalid_recovery_token' => 'Token de recuperación inválido',
        'webauthn_login_disabled' => 'Webauthn login disabled',
        'invalid_reset_token' => 'This reset token is invalid.',
        'rename_device' => 'Renombrar dispositivo',
        'my_device' => 'Mi dispositivo',
        'unknown_device' => 'Dispositivo desconocido',
        'use_webauthn_only' => [
            'label' => 'Usar WebAuthn solo',
            'help' => 'Make WebAuthn the only authorized method to log into your 2FAuth account. This is the recommended setup to take advantage of the WebAuthn enhanced security.<br /><br />
                In case of device lost, you will be able to recover your account by resetting this option and signing in using your email and password.<br /><br />
                Attention! The Email & Password form remains available despite this option being enabled, but it will always return an \'Authentication failed\' response.'
        ],
        'need_a_security_device_to_enable_options' => 'Set at least one device to enable the following options',
    ],
    'forms' => [
        'name' => 'Nombre',
        'login' => 'Iniciar sesión',
        'webauthn_login' => 'Inicio de sesión WebAuthn',
        'email' => 'E-mail',
        'password' => 'Contraseña',
        'reveal_password' => 'Revelar contraseña',
        'hide_password' => 'Ocultar contraseña',
        'confirm_password' => 'Confirmar contraseña',
        'confirm_new_password' => 'Confirma la contraseña nueva',
        'dont_have_account_yet' => '¿Aún no tienes una cuenta?',
        'already_register' => '¿Ya te has registrado?',
        'authentication_failed' => 'La autenticación ha fallado',
        'forgot_your_password' => '¿Olvidó su contraseña?',
        'request_password_reset' => 'Reiniciarlo',
        'reset_your_password' => 'Reestablecer tu contraseña',
        'reset_password' => 'Restablecer contraseña',
        'disabled_in_demo' => 'Característica desactivada en el modo Demo',
        'new_password' => 'Nueva contraseña',
        'current_password' => [
            'label' => 'Contraseña actual',
            'help' => 'Introduzca su contraseña actual para confirmar que es usted'
        ],
        'change_password' => 'Cambiar Contraseña',
        'send_password_reset_link' => 'Enviar enlace para restablecer contraseña',
        'password_successfully_changed' => 'Contraseña cambiada correctamente',
        'edit_account' => 'Editar cuenta',
        'profile_saved' => '¡Perfil actualizado con éxito!',
        'welcome_to_demo_app_use_those_credentials' => 'Bienvenido/a a la demostración de 2FAuth.<br><br>Puedes conectarte usando el email <strong>demo@2fauth.app</strong> y la contraseña <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Bienvenido/a a la instancia de prueba 2FAuth.<br><br>Usa el email <strong>testing@2fauth.app</strong> y la contraseña <strong>password</strong>',
        'register_punchline' => 'Bienvenido a <b>2FAuth</b>.<br/>Necesitas una cuenta para ir más allá, por favor, regístrate.',
        'reset_punchline' => '2FAuth le enviará un enlace para restablecer la contraseña a esta dirección. Haga clic en el enlace en el correo electrónico recibido para establecer una nueva contraseña.',
        'name_this_device' => 'Proporcione nombre al dispositivo',
        'delete_account' => 'Eliminar cuenta',
        'delete_your_account' => 'Eliminar su cuenta',
        'delete_your_account_and_reset_all_data' => 'Esto restablecerá 2FAuth. Su cuenta de usuario se eliminará, así como, todos los datos de 2FA. No hay vuelta atrás.',
        'user_account_successfully_deleted' => 'Cuenta de usuario eliminada correctamente',
        'has_lower_case' => 'Tiene minúsculas',
        'has_upper_case' => 'Tiene mayúsculas',
        'has_special_char' => 'Tiene carácter especial',
        'has_number' => 'Tiene número',
        'is_long_enough' => '8 carácteres min.',
        'mandatory_rules' => 'Obligatorio',
        'optional_rules_you_should_follow' => 'Recomendado (altamente)',
        'caps_lock_is_on' => 'Bloqueo de mayúsculas está Activado',
    ],

];
