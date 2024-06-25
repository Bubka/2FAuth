<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'settings' => 'Configurações',
    'preferences' => 'Preferências',
    'account' => 'Conta',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'Tokens',
    'options' => 'Opções',
    'user_preferences' => 'Preferências do usuário',
    'admin_settings' => 'Configurações de administração',
    'confirm' => [

    ],
    'you_are_administrator' => 'Você não é um administrador',
    'account_linked_to_sso_x_provider' => 'Você entrou via SSO usando a sua conta :provid. Sua informação não pode ser alterada aqui mas em :provider.',
    'general' => 'Geral',
    'security' => 'Segurança',
    'notifications' => 'Notificações',
    'profile' => 'Perfil',
    'change_password' => 'Mudar senha',
    'personal_access_tokens' => 'Tokens de acesso pessoal',
    'token_legend' => 'Tokens de Acesso Pessoal permitem que qualquer aplicativo se autentique na API 2Fauth. Você deve especificar o token de acesso como um token Bearer no cabeçalho de autorização das solicitações dos aplicativos.',
    'generate_new_token' => 'Gerar um novo token',
    'revoke' => 'Revogar',
    'token_revoked' => 'Token revogado com sucesso',
    'revoking_a_token_is_permanent' => 'Revogar um token é permanente',
    'confirm' => [
        'revoke' => 'Tem certeza de que deseja revogar este token?',
    ],
    'make_sure_copy_token' => 'Certifique-se de salvar seu token de acesso pessoal agora. Você não poderá vê-lo novamente!',
    'data_input' => 'Entrada de dados',
    'forms' => [
        'edit_settings' => 'Editar configurações',
        'setting_saved' => 'Configurações salvas',
        'new_token' => 'Novo token',
        'some_translation_are_missing' => 'Algumas traduções estão faltando utilizando o idioma preferido do navegador?',
        'help_translate_2fauth' => 'Ajude a traduzir 2FAuth',
        'language' => [
            'label' => 'Idioma',
            'help' => 'Idioma usado para traduzir a interface de usuário 2FAuth. Idiomas nomeados são concluídos, defina a escolha para substituir a preferência do seu navegador.'
        ],
        'timezone' => [
            'label' => 'Fuso horário',
            'help' => 'O fuso horário aplicado a todas as datas e horas exibidas na aplicação'
        ],
        'show_otp_as_dot' => [
            'label' => 'Exibir <abbr title="One-Time Password">OTP</abbr> gerado como pontos',
            'help' => 'Substitua os caracteres de senha gerados com *** para garantir a confidencialidade. Não afete o recurso copiar/colar'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Revelar <abbr title="One-Time Password">OTP</abbr> oculto',
            'help' => 'Permitir a capacidade de revelar temporariamente senhas obscurecidas por pontos'
        ],
        'close_otp_on_copy' => [
            'label' => 'Fechar <abbr title="One-Time Password">OTP</abbr> após copiar',
            'help' => 'Click on a generated password to copy it automatically hides it from the screen'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close <abbr title="One-Time Password">OTP</abbr>',
            'help' => 'Automatically hide on-screen password after a timeout. This avoids unnecessary requests for fresh passwords if you forget to close the password view.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Limpar pesquisa ao copiar',
            'help' => 'Limpar a caixa de pesquisa logo após um código ser copiado para a área de transferência'
        ],
        'sort_case_sensitive' => [
            'label' => 'Sort case sensitive',
            'help' => 'When invoked, force the Sort function to sort accounts on a case-sensitive basis'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copiar <abbr title="One-Time Password">OTP</abbr> na tela',
            'help' => 'Copiar automaticamente uma senha gerada depois que ela aparecer na tela. Devido a limitações de navegadores, apenas a primeira senha <abbr title="Time-based One-Time Password">TOTP</abbr> será copiada, não as rotativas'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'Usar leitor de QR code',
            'help' => 'Se você tiver problemas ao capturar QR code permite que essa opção mude para um leitor de QR code mais básico, mas mais confiável'
        ],
        'display_mode' => [
            'label' => 'Modo de exibição',
            'help' => 'Escolher se quer que as contas sejam exibidas como uma lista ou como grade'
        ],
        'password_format' => [
            'label' => 'Formatação de senha',
            'help' => 'Altere como as senhas são exibidas pelo agrupamento de dígitos para facilitar a legibilidade e memorização'
        ],
        'pair' => 'por Par',
        'pair_legend' => 'Agrupar dígitos dois por dois',
        'trio_legend' => 'Agrupar dígitos três por três',
        'half_legend' => 'Dividir dígitos em dois grupos iguais',
        'trio' => 'por Trio',
        'half' => 'pela Metade',
        'grid' => 'Grade',
        'list' => 'Lista',
        'theme' => [
            'label' => 'Tema',
            'help' => 'Forçar um tema específico ou aplicar o tema definido nas preferências do sistema/navegador'
        ],
        'light' => 'Claro',
        'dark' => 'Escuro',
        'automatic' => 'Automático',
        'show_accounts_icons' => [
            'label' => 'Mostrar Ícones',
            'help' => 'Exibir ícones de conta na visualização principal'
        ],
        'get_official_icons' => [
            'label' => 'Obter ícones oficiais',
            'help' => '(Tentar) Obter o ícone oficial do emissor do 2FA ao adicionar uma conta'
        ],
        'auto_lock' => [
            'label' => 'Logoff automático',
            'help' => 'Desconecta automaticamente o usuário em caso de inatividade. Não tem efeito quando a autenticação é tratada por um proxy e nenhuma URL personalizada de logout é especificada.'
        ],
        'default_group' => [
            'label' => 'Grupo padrão',
            'help' => 'O grupo ao qual as contas recém criadas serão associadas',
        ],
        'view_default_group_on_copy' => [
            'label' => 'Visualizar grupo padrão na cópia',
            'help' => 'Sempre retornar ao grupo padrão quando um OTP for copiado',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Auto-save accounts',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
        ],
        'useDirectCapture' => [
            'label' => 'Entrada direta',
            'help' => 'Escolha se você quer ser solicitado a escolher um modo de entrada entre os disponíveis ou se você quiser usar diretamente o modo de entrada padrão',
        ],
        'defaultCaptureMode' => [
            'label' => 'Modo de entrada padrão',
            'help' => 'Modo de entrada padrão usado quando a opção de entrada direta está ligada',
        ],
        'remember_active_group' => [
            'label' => 'Lembrar filtro de grupos',
            'help' => 'Salvar o último filtro de grupo aplicado e restaurá-lo na sua próxima visita',
        ],
        'otp_generation' => [
            'label' => 'Exibir senha',
            'help' => 'Defina como e quando <abbr title="One-Time Passwords">OTPs</abbr> são exibidos.<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'Em novo dispositivo',
            'help' => 'Receba um e-mail quando um novo dispositivo se conectar à sua conta 2FAuth pela primeira vez'
        ],
        'notify_on_failed_login' => [
            'label' => 'Ao acessar com falha',
            'help' => 'Receber um e-mail sempre que uma tentativa de conectar sua conta 2FAuth falhar'
        ],
        'otp_generation_on_request' => 'Após clicar/tocar',
        'otp_generation_on_request_legend' => 'Sozinho, na sua própria exibição',
        'otp_generation_on_request_title' => 'Clique em uma conta para obter uma senha em uma exibição dedicada',
        'otp_generation_on_home' => 'Constantemente',
        'otp_generation_on_home_legend' => 'Todos eles, em casa',
        'otp_generation_on_home_title' => 'Mostrar todas as senhas na visualização principal, sem fazer nada',
        'never' => 'Nunca',
        'on_otp_copy' => 'Quando copiar código de segurança',
        '1_minutes' => 'Após 1 minuto',
        '2_minutes' => 'After 2 minutes',
        '5_minutes' => 'Após 5 minutos',
        '10_minutes' => 'Após 10 minutos',
        '15_minutes' => 'Após 15 minutos',
        '30_minutes' => 'Após 30 minutos',
        '1_hour' => 'Após 1 hora',
        '1_day' => 'Após 1 dia',
        'livescan' => 'QR code livescan',
        'upload' => 'QR code upload',
        'advanced_form' => 'Forma avançada',
    ],

];