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
    'settings_managed_by_administrator' => 'Algumas configurações estão sendo gerenciadas pelo seu administrador',
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
            'label' => 'Mostrar OTP\'s  como pontos',
            'help' => 'Substitua os caracteres da senha gerada por *** para garantir a confidencialidade. Isso não afeta o recurso de copiar/colar'
        ],
        'reveal_dotted_otp' => [
            'label' => 'Revelar OTP ocultado',
            'help' => 'Permitir a capacidade de revelar temporariamente senhas obscurecidas por pontos'
        ],
        'close_otp_on_copy' => [
            'label' => 'Fechar OTP após cópia',
            'help' => 'Clicar para copiar uma senha gerada a ocultará automaticamente da tela'
        ],
        'show_next_otp' => [
            'label' => 'Mostrar próxima OTP',
            'help' => 'Visualize a próxima senha, ou seja, a senha que substituirá a senha atual quando ela expirar. As preferências definidas para a OTP atual também se aplicam à próxima (formatação, exibir como ponto).
'
        ],
        'auto_close_timeout' => [
            'label' => 'Fechar OTP Automaticamente',
            'help' => 'Ocultar automaticamente a senha na tela após um tempo limite. Isso evita solicitações desnecessárias de novas senhas se você esquecer de fechar a tela de senha.'
        ],
        'clear_search_on_copy' => [
            'label' => 'Limpar pesquisa ao copiar',
            'help' => 'Limpar a caixa de pesquisa logo após um código ser copiado para a área de transferência'
        ],
        'sort_case_sensitive' => [
            'label' => 'Ordenar com distinção entre maiúsculas e minúsculas',
            'help' => 'Quando acionado, forçar a função Ordenar a organizar as contas de forma que diferencie entre maiúsculas e minúsculas'
        ],
        'copy_otp_on_display' => [
            'label' => 'Copiar automaticamente ao abrir OTP',
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
            'help' => 'Exibir ícones das contas na visão das contas'
        ],
        'get_official_icons' => [
            'label' => 'Obter ícones oficiais',
            'help' => '(Tentar) Obter o ícone oficial do emissor do 2FA ao adicionar uma conta?'
        ],
        'icon_collection' => [
            'label' => 'Favorite icon source',
            'help' => 'The icons collection to be queried at first when an official icon is required. Changing this setting does not refresh icons that have already been fetched.'
        ],
        'icon_variant' => [
            'label' => 'Icon variant',
            'help' => 'Some icons are available in different flavors to best suit dark or light user interfaces. Set the one you want to look for first. The regular variant will automatically be fetched as a fallback.'
        ],
        'icon_variant_strict_fetch' => [
            'label' => 'Strict fetch',
            'help' => 'Narrow the fetch to the specified variant. If the variant is missing, 2FAuth will not try to fallback to the regular variant.'
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
            'label' => 'Salvar contas automaticamente',
            'help' => 'Novas contas são registradas automaticamente após a verificação ou envio de um código QR, não é necessário clicar num botão Salvar',
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
        'show_email_in_footer' => [
            'label' => 'Exibir e-mail no rodapé?',
            'help' => 'Exiba o e-mail do usuário logado no rodapé em vez de links de navegação diretos. Neste caso os links ficam disponíveis em um menu acessível ao clicar/tocar no endereço de e-mail.'
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
        '2_minutes' => 'Após 2 minutos',
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