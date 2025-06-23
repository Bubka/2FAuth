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

    'service' => 'Serviço',
    'account' => 'Conta',
    'icon' => 'Ícone',
    'icon_to_illustrate_the_account' => 'Ícone que ilustra a conta',
    'remove_icon' => 'Excluir ícone',
    'no_account_here' => 'Sem 2FA aqui!',
    'add_first_account' => 'Escolha um método e adicione sua primeira conta',
    'use_full_form' => 'Ou utilize o formulário completo',
    'add_one' => 'Adicione um',
    'show_qrcode' => 'Mostrar QR Code',
    'no_service' => '- sem serviço -',
    'account_created' => 'Conta criada com sucesso!',
    'account_updated' => 'Conta atualizada com sucesso',
    'accounts_deleted' => 'Conta(s) excluídas com êxito',
    'accounts_moved' => 'Conta(s) movidas com sucesso',
    'shared_account_indicator' => 'Conta compartilhada',
    'export_selected_accounts' => 'Exportar contas selecionadas',
    'twofauth_export_format' => 'Formato 2FAuth',
    'twofauth_export_format_sub' => 'Exporte dados usando o esquema JSON do 2FAuth






',
    'twofauth_export_format_desc' => 'Você pode optar por esta opção caso você precisar criar um backup que possa ser restaurado. Este formato inclui ícones.',
    'twofauth_export_format_url' => 'A descrição de definição do esquema:',
    'twofauth_export_schema' => 'Esquema de exportação do 2FAuth',
    'otpauth_export_format' => 'Parametros da URIs de otpauth',
    'otpauth_export_format_sub' => 'Exporte dados em lista de URIs otpauth',
    'otpauth_export_format_desc' => 'A URI otpauth é um formato padronizado para compartilhar dados de autenticação de dois fatores (2FA). Esse formato é amplamente utilizado por serviços e aplicativos para facilitar a configuração do 2FA por meio de QR codes. Quando você ativa o 2FA em um site, ele normalmente exibe um código QR que encapsula uma URI otpauth, permitindo que o aplicativo de autenticação (como Google Authenticator, Authy, ou Microsoft Authenticator) escaneie o código e configure automaticamente o 2FA para sua conta.

Estrutura da URI otpauth
A URI otpauth é composta de informações essenciais para a configuração do 2FA e segue este formato básico:

Exemplo:
otpauth://TYPE/LABEL?secret=SECRET&issuer=ISSUER&digits=DIGITS&period=PERIOD',
    'reveal' => 'revelar',
    'forms' => [
        'service' => [
            'placeholder' => 'Google, Twitter, Apple',
        ],
        'account' => [
            'placeholder' => 'João da Silva',
        ],
        'new_account' => 'Nova conta',
        'edit_account' => 'Editar conta',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => 'Escanear QR code',
        'upload_qrcode' => 'Enviar um código QR',
        'use_advanced_form' => 'Use o formulário avançado',
        'prefill_using_qrcode' => 'Prepreencha usando um código QR',
        'use_qrcode' => [
            'val' => 'Use um código QR',
            'title' => 'Use um código QR para preencher o formulário magicamente',
        ],
        'unlock' => [
            'val' => 'Desbloquear',
            'title' => 'Desbloqueie (por sua conta e risco)',
        ],
        'lock' => [
            'val' => 'Bloquear',
            'title' => 'Bloquear',
        ],
        'choose_image' => 'Enviar',
        'i_m_lucky' => 'Tente a minha sorte',
        'i_m_lucky_legend' => 'The "Try my luck" button tries to get a standard icon from the selected icon collection. The simpler the Service field value, the more likely you are to get the expected icon: Do not append any extension (like ".com"), use the exact name of the service, avoid special chars.',
        'test' => 'Testar',
        'group' => [
            'label' => 'Grupo',
            'help' => 'O grupo ao qual a conta deve ser atribuída'
        ],
        'secret' => [
            'label' => 'Chave',
            'help' => 'A chave usada para gerar os seus códigos de segurança'
        ],
        'plain_text' => 'Texto sem formatação',
        'otp_type' => [
            'label' => 'Escolha o método de OTP que deseja criar',
            'help' => 'OTP baseado no tempo ou HMAC baseado em OTP ou OTP Steam'
        ],
        'digits' => [
            'label' => 'Dígitos',
            'help' => 'O número de dígitos dos códigos de segurança gerados'
        ],
        'algorithm' => [
            'label' => 'Algoritmo',
            'help' => 'O algoritmo usado para proteger seus códigos de segurança'
        ],
        'period' => [
            'label' => 'Validade',
            'placeholder' => 'O padrão é 30',
            'help' => 'O período de validade dos códigos de segurança gerados no segundo'
        ],
        'counter' => [
            'label' => 'Contador',
            'placeholder' => 'O padrão é 0.',
            'help' => 'O valor inicial do contador',
            'help_lock' => 'É arriscado editar o contador, pois você pode dessincronizar a conta com o servidor de verificação do serviço. Use o ícone de bloqueio para ativar a modificação, mas apenas se você sabe que está fazendo'
        ],
        'image' => [
            'label' => '',
            'placeholder' => 'http://...',
            'help' => 'A URL de uma imagem externa para usar como ícone de conta'
        ],
        'is_shared' => [
            'label' => 'Compartilhar esta conta com todos os usuários',
            'help' => 'Quando habilitado, esta conta será visível para todos os usuários do sistema'
        ],
        'options_help' => 'Você pode deixar as seguintes opções em branco se você não sabe como configurá-los. Os valores mais usados serão aplicados.',
        'alternative_methods' => 'Métodos alternativos',
        'spaces_are_ignored' => 'Espaços indesejados serão removidos automaticamente'
    ],
    'stream' => [
        'live_scan_cant_start' => 'Verificação ao vivo não pode iniciar :(',
        'need_grant_permission' => [
            'reason' => 'O 2FAuth não tem permissão para acessar sua câmera',
            'solution' => 'Você precisa conceder permissão para usar a câmera do seu dispositivo. Se você já negou e o seu navegador não solicita novamente, consulte a documentação do navegador para descobrir como conceder a permissão.',
            'click_camera_icon' => 'É geralmente feito clicando no ícone da câmera na barra de endereços do navegador ou ao lado da barra de endereços',
        ],
        'not_readable' => [
            'reason' => 'Falha ao carregar o scanner',
            'solution' => 'A câmera já está em uso? Certifique-se de que nenhum outro app usa a câmera e tente novamente'
        ],
        'no_cam_on_device' => [
            'reason' => 'Nenhuma câmera neste dispositivo',
            'solution' => 'Talvez você tenha se esquecido de conectar sua câmera.'
        ],
        'secured_context_required' => [
            'reason' => 'Contexto HTTPS requerido',
            'solution' => 'HTTPS é necessário para varredura ao vivo. Se você executar 2FAuth a partir do seu computador, não use outro host virtual além do localhost'
        ],
        'https_required' => 'HTTPS necessário para streaming de câmera',
        'camera_not_suitable' => [
            'reason' => 'Câmeras instaladas não são adequadas',
            'solution' => 'Por favor, use outro dispositivo/câmera'
        ],
        'stream_api_not_supported' => [
            'reason' => 'Stream API não é suportada neste navegador',
            'solution' => 'Você deve usar um navegador moderno'
        ],
    ],
    'confirm' => [
        'delete' => 'Tem certeza de que quer apagar esta conta?',
        'cancel' => 'As alterações serão perdidas. Tem certeza?',
        'discard' => 'Tem certeza que deseja excluir sua conta?',
        'discard_all' => 'Tem certeza de que deseja excluir todas as contas?',
        'discard_duplicates' => 'Você tem certeza que deseja excluir todas as duplicatas?',
    ],
    'import' => [
        'import' => 'Importar',
        'to_import' => 'Importar',
        'import_legend' => '2FAuth pode importar dados de vários aplicativos 2FA.',
        'import_legend_afterpart' => 'Use o recurso de exportação destes aplicativos para obter um recurso de migração como um código QR ou um arquivo JSON, então carregue-o aqui.',
        'upload' => 'Carregar',
        'scan' => 'Ler',
        'supported_formats_for_qrcode_upload' => 'Aceito: jpg, jpeg, png, bmp, gif, svg ou webp',
        'supported_formats_for_file_upload' => 'Aceito: Texto simples, json, 2fas',
        'expected_format_for_direct_input' => 'Esperado: Uma lista de otpauth URI, uma por linha',
        'supported_migration_formats' => 'Formatos de migração suportados',
        'qr_code' => 'Código QR',
        'text_file' => 'Arquivo de texto',
        'direct_input' => 'Entrada direta',
        'plain_text' => 'Texto sem formatação',
        'parsing_data' => 'Processando dados...',
        'issuer' => 'Emissor',
        'imported' => 'Importado',
        'failure' => 'Falha',
        'x_valid_accounts_found' => 'Encontradas :count contas válidas',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'As seguintes contas 2FA foram encontradas no recurso de migração. Até agora, nenhuma delas foi adicionada ao 2FAuth.',
        'use_buttons_to_save_or_discard' => 'Use os botões disponíveis para salvá-los permanentemente em sua coleção de 2FA ou descartá-los.',
        'import_all' => 'Importar todos',
        'import_this_account' => 'Importar esta conta',
        'discard_all' => 'Descartar tudo',
        'discard_duplicates' => 'Descartar duplicatas',
        'discard_this_account' => 'Descartar esta conta',
        'generate_a_test_password' => 'Gerar senha de teste',
        'possible_duplicate' => 'Uma conta com os mesmos dados já existe',
        'invalid_account' => '- conta inválida -',
        'invalid_service' => '- serviço inválido -',
        'do_not_set_password_or_encryption' => 'Não habilite a proteção de senha ou criptografia quando você exportar dados de um aplicativo 2FA caso contrário, 2FAuth não será capaz de decifrá-los.',
    ],

];