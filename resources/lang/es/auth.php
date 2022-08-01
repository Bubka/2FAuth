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
    'login_and_password' => 'usuario y contraseña',
    'register' => 'Registrarse',
    'welcome_back_x' => 'Bienvenido/a otra vez {0}',
    'autolock_triggered' => 'Autobloqueo provocado',
    'autolock_triggered_punchline' => 'El evento observándola por la función de bloqueo automático se ha activado. Has sido desconectado automáticamente.',
    'change_autolock_in_settings' => 'Puede cambiar el comportamiento de la función de bloqueo automático en la pestaña Configuración > Opciones.',
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
        'security_devices_legend' => 'Dispositivos de autenticación que pude usar para iniciar sesión en 2FAuth, como: llaves de seguridad (ej. Yubikey) o smartphones con capacidades biométricas (ej. Apple FaceID/TouchID)',
        'enhance_security_using_webauthn' => 'Puede mejorar la seguridad de su cuenta 2FAuth activando la autenticación WebAuthn.<br /><br />
            WebAuthn permite el uso de dispositivos de confianza (como, Yubikeys o smartphones con capacidades biométricas) para iniciar sesión rápidamente y de forma más segura.',
        'use_security_device_to_sign_in' => 'Prepárese para autenticarse usando (uno) de sus dispositivos de seguridad. Conecte su llave, retirar máscaras o guantes, etc.',
        'lost_your_device' => '¿Perdió su dispositivo?',
        'recover_your_account' => 'Recuperar su cuenta',
        'account_recovery' => 'Recuperación cuenta',
        'recovery_punchline' => '2FAuth enviará un enlace de recuperación a esta dirección de correo. Clic en el enlace recibido para registrar un nuevo dispositivo de seguridad.<br /><br />Asegúrese de abrir el correo en un dispositivo de confianza.',
        'send_recovery_link' => 'Enviar enlace de recuperación',
        'account_recovery_email_sent' => '¡Correo de recuperación de cuenta enviado!',
        'disable_all_other_devices' => 'Desactivar el resto de dispositivos, excepto éste',
        'register_a_new_device' => 'Registrar un nuevo dispositivo',
        'device_successfully_registered' => 'Dispositivo registrado correctamente',
        'device_revoked' => 'Dispositivo revocado correctamente',
        'revoking_a_device_is_permanent' => 'El revocado de un dispositivo es permanente',
        'recover_account_instructions' => 'Clic en el botón de abajo para registrar un nuevo dispositivo para recuperar su cuenta. Simplemente, siga las instrucciones del navegador.',
        'invalid_recovery_token' => 'Token de recuperación inválido',
        'rename_device' => 'Renombrar dispositivo',
        'my_device' => 'Mi dispositivo',
        'unknown_device' => 'Dispositivo desconocido',
        'use_webauthn_only' => [
            'label' => 'Usar WebAuthn solo',
            'help' => 'Hacer que WebAuthn sea el único método para iniciar sesión disponible en 2FAuth. Esta es la configuración recomendada para aprovechar la mejora en la seguridad de WebAuthn.<br />
                En caso de pérdida del dispositivo, siempre tendrá la posibilidad de registrar un nuevo dispositivo de seguridad para recuperar su cuenta.'
        ],
        'need_a_security_device_to_enable_options' => 'Establezca, al menos, un dispositivo para activar estas opciones',
        'use_webauthn_as_default' => [
            'label' => 'Usar WebAuthn como método de inicio de sesión predeterminado',
            'help' => 'Establezca el formulario de inicio de sesión de 2FAuth para proponer la autenticación WebAuthn en primer lugar. El método Login/password está entonces disponible como solución alternativa o de retorno.<br />
                Esto no tiene efecto si sólo utiliza WebAuthn.'
        ],
    ],
    'forms' => [
        'name' => 'Nombre',
        'login' => 'Iniciar sesión',
        'webauthn_login' => 'Inicio de sesión WebAuthn',
        'email' => 'E-mail',
        'password' => 'Contraseña',
        'confirm_password' => 'Confirmar contraseña',
        'confirm_new_password' => 'Confirma la contraseña nueva',
        'dont_have_account_yet' => '¿Aún no tienes una cuenta?',
        'already_register' => '¿Ya te has registrado?',
        'authentication_failed' => 'La autenticación ha fallado',
        'forgot_your_password' => '¿Olvidó su contraseña?',
        'request_password_reset' => 'Reiniciarlo',
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
        'welcome_to_demo_app_use_those_credentials' => 'Bienvenido a la demostración de 2FAuth.<br><br>Puede conectarse usando el email <strong>demo@2fauth.app</strong> y la contraseña <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Bienvenido a la instancia de prueba 2FAuth.<br><br>Usar email <strong>testing@2fauth.app</strong> y contraseña <strong>password</strong>',
        'register_punchline' => 'Bienvenido a 2FAuth.<br/>Necesita una cuenta para ir más allá. Rellene este formulario para registrarse y, por favor, elija una contraseña fuerte, los datos de 2FA son sensibles.',
        'reset_punchline' => '2FAuth le enviará un enlace para restablecer la contraseña a esta dirección. Haga clic en el enlace en el correo electrónico recibido para establecer una nueva contraseña.',
        'name_this_device' => 'Proporcione nombre al dispositivo',
        'delete_account' => 'Eliminar cuenta',
        'delete_your_account' => 'Eliminar su cuenta',
        'delete_your_account_and_reset_all_data' => 'Esto restablecerá 2FAuth. Su cuenta de usuario se eliminará, así como, todos los datos de 2FA. No hay vuelta atrás.',
        'user_account_successfully_deleted' => 'Cuenta de usuario eliminada correctamente',
    ],

];
