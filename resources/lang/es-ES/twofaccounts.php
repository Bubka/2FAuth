<?php

return [

      'add_one' => 'Añadir una',
    'show_qrcode' => 'Mostrar código QR',
    'no_service' => '- sin servicio -',
    'account_created' => 'Cuenta creada correctamente',
    'account_updated' => 'Cuenta actualizada correctamente',
    'accounts_deleted' => 'Cuenta(s) eliminada(s) correctamente',
    'accounts_moved' => 'Cuenta(s) movida(s) correctamente',
    'shared_account_indicator' => 'Cuenta compartida',
    'export_selected_accounts' => 'Export selected accounts',
    'accounts_moved' => 'Cuenta(s) movida(s) correctamente',
    'export_selected_accounts' => 'Export selected accounts',
    'twofauth_export_format' => '2FAuth format',
    'twofauth_export_format_sub' => 'Export data using the 2FAuth json schema',
    'twofauth_export_format_desc' => 'You should prefer this option if you need to create a backup that can be restored. This format takes care of the icons.',
    'twofauth_export_format_url' => 'The schema definition is described here:',
    'twofauth_export_schema' => '2FAuth export schema',
    'otpauth_export_format' => 'otpauth URIs',
    'otpauth_export_format_sub' => 'Export data as a list of otpauth URIs',
    'otpauth_export_format_desc' => 'otpauth URI is the most common format used to exchange 2FA data, for example in the form of a QR code when you enable 2FA on a web site. Select this if you want to switch from 2FAuth.',
    'reveal' => 'mostrar',
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
        'i_m_lucky' => 'Probar suerte',
        'i_m_lucky_legend' => 'The "Try my luck" button tries to get a standard icon from the selected icon collection. The simpler the Service field value, the more likely you are to get the expected icon: Do not append any extension (like ".com"), use the exact name of the service, avoid special chars.',
        'test' => 'Test',
        'group' => [
            'label' => 'Group',
            'help' => 'The group to which the account is to be assigned'
        ],
        'secret' => [
            'label' => 'Secreto',
            'help' => 'La clave utilizada para generar sus códigos de seguridad'
        ],
        'plain_text' => 'Texto plano',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
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
        'is_shared' => [
            'label' => 'Compartir esta cuenta con todos los usuarios',
            'help' => 'Cuando esté habilitado, esta cuenta será visible para todos los usuarios del sistema'
        ],
        'options_help' => 'Puede dejar las siguientes opciones en blanco si no sabe cómo establecerlas. Los valores más utilizados se aplicarán.',
        'alternative_methods' => 'Métodos alternativos',
        'spaces_are_ignored' => 'Unwanted spaces will be automatically removed'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Live scan no puede comenzar :(',
        'need_grant_permission' => [
            'reason' => '2FAuth no tiene permiso para acceder a tu cámara',
            'solution' => 'Necesitas conceder permiso para usar la cámara de tu dispositivo. Si ya lo ha denegado y su navegador no le preguntan de nuevo, por favor refiérase a la documentación del navegador para averiguar cómo conceder permisos.',
            'click_camera_icon' => 'Normalmente se realiza haciendo clic en el icono de la cámara en o al lado de la barra de direcciones del navegador',
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
        'import_legend_afterpart' => 'Use la función Exportar de estas aplicaciones para obtener un recurso de migración como un código QR o un archivo JSON y luego cárguelo aquí.',
        'upload' => 'Subir',
        'scan' => 'Escanear',
        'supported_formats_for_qrcode_upload' => 'Aceptado: jpg, jpeg, png, bmp, gif, svg, o webp',
        'supported_formats_for_file_upload' => 'Aceptado: texto plano, json, 2fas',
        'expected_format_for_direct_input' => 'Expected: A list of otpauth URI, one by line',
        'supported_migration_formats' => 'Formatos de migración soportados',
        'qr_code' => 'Código QR',
        'text_file' => 'Fichero de texto',
        'direct_input' => 'Direct input',
        'plain_text' => 'Texto plano',
        'parsing_data' => 'Analizando datos...',
        'issuer' => 'Emisor',
        'imported' => 'Importado',
        'failure' => 'Fallo',
        'x_valid_accounts_found' => '{count} cuentas válidos encontrados',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'Las siguientes cuentas de 2FA fueron encontradas en el recurso de migración. Hasta ahora, ninguna de ellas ha sido añadida a 2FAuth.',
        'use_buttons_to_save_or_discard' => 'Utilice los botones disponibles para guardarlos permanentemente en su colección de 2FA o descartarlos.',
        'import_all' => 'Importar todo',
        'import_this_account' => 'Importar esta Cuenta',
        'discard_all' => 'Descartar todo',
        'discard_duplicates' => 'Descartar duplicados',
        'discard_this_account' => 'Descartar esta cuenta',
        'generate_a_test_password' => 'Generar una contraseña de prueba',
        'possible_duplicate' => 'Ya existe una cuenta con exactamente los mismos datos',
        'invalid_account' => '- cuenta inválida -',
        'invalid_service' => '- servicio inválido -',
        'do_not_set_password_or_encryption' => 'NO habilite la protección de contraseña o el cifrado cuando exporte datos (desde una aplicación 2FA) que desee importar a 2FAuth.',
    ],

];