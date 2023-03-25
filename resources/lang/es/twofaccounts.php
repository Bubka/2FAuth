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

    'service' => 'Servicio',
    'account' => 'Cuenta',
    'accounts' => 'Cuentas',
    'icon' => 'Icono',
    'icon_for_account_x_at_service_y' => 'Icono de la cuenta {account} en {service}',
    'icon_to_illustrate_the_account' => 'Icono que representa a la cuenta',
    'remove_icon' => 'Eliminar icono',
    'no_account_here' => '¡No hay 2FA aquí!',
    'add_first_account' => 'Elige un método y añade tu primer cuenta',
    'use_full_form' => 'O usa el formulario completo',
    'add_one' => 'Agregar uno',
    'show_qrcode' => 'Muestra el código QR',
    'no_service' => '- sin servicio -',
    'account_created' => 'Cuenta creada correctamente',
    'account_updated' => 'Cuenta actualizada correctamente',
    'accounts_deleted' => 'Cuenta(s) eliminada(s) correctamente',
    'accounts_moved' => 'Cuenta(s) movida(s) correctamente',
    'export_selected_to_json' => 'Download a json export of selected accounts',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'John DOE',
        ],
        'new_account' => 'Nueva cuenta',
        'edit_account' => 'Editar cuenta',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Escanear un código QR',
        'upload_qrcode' => 'Subir un código QR',
        'use_advanced_form' => 'Usar el formulario avanzado',
        'prefill_using_qrcode' => 'Rellenar usando un código QR',
        'use_qrcode' => [
            'val' => 'Usar un código QR',
            'title' => 'Usa un código QR para rellenar mágicamente el formulario',
        ],
        'unlock' => [
            'val' => 'Desbloquear',
            'title' => 'Desbloquearlo (bajo su propio riesgo)',
        ],
        'lock' => [
            'val' => 'Bloquear',
            'title' => 'Bloquearlo',
        ],
        'choose_image' => 'Subir',
        'i_m_lucky' => 'Tengo suerte',
        'i_m_lucky_legend' => 'El botón "Tengo suerte" intenta obtener el icono oficial del servicio dado. Introduzca el nombre del servicio sin la extensión ".xyz" e intente evitar errores tipográficos. (función beta)',
        'test' => 'Test',
        'secret' => [
            'label' => 'Secreto',
            'help' => 'La clave utilizada para generar sus códigos de seguridad'
        ],
        'plain_text' => 'Texto plano',
        'otp_type' => [
            'label' => 'Elige el tipo de <abbr title="One-Time Password">OTP</abbr> a crear',
            'help' => 'Time-based OTP, HMAC-based OTP o Steam OTP'
        ],
        'digits' => [
            'label' => 'Dígitos',
            'help' => 'El número de dígitos del código de seguridad generado'
        ],
        'algorithm' => [
            'label' => 'Algorítmo',
            'help' => 'El algoritmo usado para proteger sus códigos de seguridad'
        ],
        'period' => [
            'label' => 'Periodo',
            'placeholder' => 'Por defecto es 30',
            'help' => 'Periodo de validez de los códigos se seguridad generados, en segundos'
        ],
        'counter' => [
            'label' => 'Contador',
            'placeholder' => 'Por defecto es 0',
            'help' => 'El valor inicial del contador',
            'help_lock' => 'Es arriesgado editar el contador, ya que puede desincronizar la cuenta con el servidor de verificación del servicio. Utilice el icono de bloqueo para habilitar la modificación, pero solo si sabe lo que está haciendo'
        ],
        'image' => [
            'label' => 'Imágen',
            'placeholder' => 'http://...',
            'help' => 'La url de una imagen externa a usar como icono de cuenta'
        ],
        'options_help' => 'Puede dejar las siguientes opciones en blanco si no sabe cómo establecerlas. Los valores más utilizados se aplicarán.',
        'alternative_methods' => 'Métodos alternativos',
    ],
    'stream' => [
        'live_scan_cant_start' => 'Live scan no puede comenzar :(',
        'need_grant_permission' => [
            'reason' => '2FAuth no tiene permiso para acceder a tu cámara',
            'solution' => 'Necesitas conceder permiso para usar la cámara de tu dispositivo. Si ya lo ha denegado y su navegador no le preguntan de nuevo, por favor refiérase a la documentación del navegador para averiguar cómo conceder permisos.'
        ],
        'not_readable' => [
            'reason' => 'Fallo al cargar el escáner',
            'solution' => '¿La cámara ya está en uso? Asegúrate de que ninguna otra aplicación use tu cámara e inténtalo de nuevo'
        ],
        'no_cam_on_device' => [
            'reason' => 'No se encontraron cámaras en este dispositivo',
            'solution' => 'Tal vez olvidaste conectar tu cámara web'
        ],
        'secured_context_required' => [
            'reason' => 'Requiere contexto seguro',
            'solution' => 'HTTPS es necesario para escanear en vivo. Si ejecuta 2FAuth desde su computadora, no utilice un host virtual distinto de localhost'
        ],
        'https_required' => 'HTTPS requerido para la transmisión de la cámara',
        'camera_not_suitable' => [
            'reason' => 'Las cámaras instaladas no son apropiadas',
            'solution' => 'Por favor usa otro dispositivo/cámara'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API no está soportado en este navegador',
            'solution' => 'Deberías usar un navegador moderno'
        ],
    ],
    'confirm' => [
        'delete' => '¿Está seguro que desea eliminar esta cuenta?',
        'cancel' => 'La cuenta será eliminada. ¿Estás seguro?',
        'discard' => '¿Está seguro que desea eliminar esta cuenta?',
        'discard_all' => '¿Está seguro que desea eliminar todas las cuentas?',
        'discard_duplicates' => '¿Está seguro que desea eliminar todos los duplicados?',
    ],
    'import' => [
        'import' => 'Import',
        'to_import' => 'Importar',
        'import_legend' => '2FAuth puede importar datos de varias apps 2FA.<br />Usa la función de Exportación de éstas apps para generar los recursos de migración (código QR o archivo), y cargalo usando el método preferido abajo.',
        'upload' => 'Subir',
        'scan' => 'Escanear',
        'supported_formats_for_qrcode_upload' => 'Aceptado: jpg, jpeg, png, bmp, gif, svg, o webp',
        'supported_formats_for_file_upload' => 'Aceptado: texto plano, json, 2fas',
        'supported_migration_formats' => 'Formatos de migración soportados',
        'qr_code' => 'Código QR',
        'plain_text' => 'Texto plano',
        'issuer' => 'Emisor',
        'imported' => 'Importado',
        'failure' => 'Fallo',
        'x_valid_accounts_found' => '{count} cuentas válidos encontrados',
        'import_all' => 'Importar todo',
        'import_this_account' => 'Importar esta Cuenta',
        'discard_all' => 'Descartar todo',
        'discard_duplicates' => 'Descartar duplicados',
        'discard_this_account' => 'Descartar esta cuenta',
        'generate_a_test_password' => 'Generar una contraseña de prueba',
        'possible_duplicate' => 'Ya existe una cuenta con exactamente los mismos datos',
        'invalid_account' => '- cuenta inválida -',
        'invalid_service' => '- servicio inválido -',
        'do_not_set_password_or_encryption' => 'Do NOT enable Password protection or Encryption when you export data (from a 2FA app) you want to import into 2FAuth.',
    ],

];