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
    'failed' => 'Essas credenciais não correspondem aos nossos registros.',
    'password' => 'A senha inserida está incorreta.',
    'throttle' => 'Muitas tentativas de login. Por favor, tente novamente em :seconds segundos.',

    // 2FAuth
    'sign_out' => 'Encerrar Sessão',
    'sign_in' => 'Entrar',
    'sign_in_using' => 'Entrar usando',
    'if_administrator' => 'Administrador?',
    'sign_in_here' => 'Você pode fazer entrar sem SSO.',
    'or_continue_with' => 'Você também pode continuar com:',
    'password_login_and_webauthn_are_disabled' => 'O login com senha e o WebAuthn estão desativados.',
    'sign_in_using_sso' => 'Escolha um provedor de SSO para fazer login:',
    'no_provider' => 'Não existe provedor',
    'no_sso_provider_or_provider_is_missing' => 'O provedor está faltando?',
    'see_how_to_enable_sso' => 'Veja como habilitar um provedor',
    'sign_in_using_security_device' => 'Fazer login usando um dispositivo de segurança',
    'login_and_password' => 'usuário e senha',
    'register' => 'Cadastro',
    'welcome_to_2fauth' => 'Bem-vindo(a) ao 2FAuth',
    'autolock_triggered' => 'Bloqueio automático acionado',
    'autolock_triggered_punchline' => 'Bloqueio automático acionado, você foi desconectado',
    'already_authenticated' => 'Já autenticado, por favor, saia primeiro',
    'authentication' => 'Autenticação',
    'maybe_later' => 'Farei isso depois',
    'user_account_controlled_by_proxy' => 'Conta do usuário disponibilizada por um proxy de autenticação.<br />Gerencie a conta no nível de proxy.',
    'auth_handled_by_proxy' => 'Autenticação manipulada por um proxy reverso, as configurações abaixo estão desativadas.<br />Gerenciar autenticação no nível do proxy.',
    'sso_only_x_settings_are_disabled' => 'A autenticação está restrita apenas ao SSO, :auth_method está desativado.',
    'confirm' => [
        'logout' => 'Tem certeza que deseja sair?',
        'revoke_device' => 'Tem certeza de que deseja excluir este dispositivo?',
        'delete_account' => 'Tem certeza que deseja excluir sua conta?',
    ],
    'webauthn' => [
        'security_device' => 'um dispositivo de segurança',
        'security_devices' => 'Dispositivos de segurança',
        'security_devices_legend' => 'Os dispositivos de autenticação que você pode usar para entrar no 2FAuth, como chaves de segurança (ex: Yubikey) ou smartphones com recursos biométricos (ex: Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'Você pode aumentar a segurança da sua conta 2FAuth ativando a autenticação WebAuthn.<br /><br />
            WebAuthn permite que você use dispositivos confiáveis (como Yubikeys ou smartphones com capacidade biométrica) para entrar de forma rápida e segura.',
        'use_security_device_to_sign_in' => 'Prepare-se para autenticar usando (um dos) seus dispositivos de segurança. Conecte sua chave, remova a máscara de rosto ou luvas, etc.',
        'lost_your_device' => 'Perdeu seu dispositivo?',
        'recover_your_account' => 'Recupere sua conta',
        'account_recovery' => 'Recuperar conta',
        'recovery_punchline' => '2FAuth enviará um link de recuperação para este endereço de e-mail. Clique no link no e-mail recebido e siga as instruções.<br /><br />Certifique-se de abrir o e-mail em um dispositivo que você possui.',
        'send_recovery_link' => 'Enviar link de recuperação',
        'account_recovery_email_sent' => 'E-mail de recuperação da conta enviado!',
        'disable_all_security_devices' => 'Desativar todos os dispositivos de segurança',
        'disable_all_security_devices_help' => 'Todos os seus dispositivos de segurança serão revogados. Use esta opção se você perdeu um ou sua segurança foi comprometida.',
        'register_a_new_device' => 'Registrar um novo dispositivo',
        'register_a_device' => 'Registra um dispositivo',
        'device_successfully_registered' => 'Dispositivo registrado com sucesso',
        'device_revoked' => 'Dispositivo revogado com sucesso',
        'revoking_a_device_is_permanent' => 'Revogar um dispositivo é permanente',
        'recover_account_instructions' => 'Para recuperar sua conta, o 2FAuth redefine algumas configurações de Webauthn para que possa entrar usando seu e-mail e senha.',
        'invalid_recovery_token' => 'Token de recuperação inválido',
        'webauthn_login_disabled' => 'Login via Webauthn desativado',
        'invalid_reset_token' => 'Esse token de redefinição é válido.',
        'rename_device' => 'Renomear dispositivo',
        'my_device' => 'Meu dispositivo',
        'unknown_device' => 'Dispositivo desconhecido',
        'use_webauthn_only' => [
            'label' => 'Usar somente o WebAuthn',
            'help' => 'Faça do WebAuthn o único método autorizado para entrar em sua conta 2FAuth. Essa é a configuração recomendada para aproveitar a segurança reforçada da WebAuthn.<br /><br />
                no caso de dispositivo perdido, você poderá recuperar sua conta redefinindo esta opção e entrando usando seu e-mail e senha.<br /><br />
                Atenção! O formulário de e-mail e senha permanece disponível apesar de esta opção estar ativada, mas ele sempre retornará uma resposta \'Autenticação falhou\'.'
        ],
        'need_a_security_device_to_enable_options' => 'Defina pelo menos um dispositivo para ativar as seguintes opções',
        'options' => 'Opções',
    ],
    'forms' => [
        'name' => 'Nome',
        'login' => 'Entrar',
        'webauthn_login' => 'Entrar com WebAuthn',
        'sso_login' => 'Login com SSO',
        'email' => 'E-mail',
        'password' => 'Senha',
        'reveal_password' => 'Mostrar senha',
        'hide_password' => 'Ocultar Senha',
        'confirm_password' => 'Confirmar senha',
        'new_password' => 'Nova senha',
        'confirm_new_password' => 'Confirmar nova senha',
        'dont_have_account_yet' => 'Ainda não tem uma conta?',
        'already_register' => 'Já possui cadastro?',
        'authentication_failed' => 'Falha na autenticação',
        'forgot_your_password' => 'Esqueceu sua senha?',
        'request_password_reset' => 'Redefinir',
        'reset_your_password' => 'Redefinir sua senha',
        'reset_password' => 'Redefinir senha',
        'disabled_in_demo' => 'Recurso desativado no modo Demonstração',
        'sso_only_form_restricted_to_admin' => 'Usuários comuns devem fazer login com SSO. Outros métodos são apenas para administradores.',
        'new_password' => 'Nova senha',
        'current_password' => [
            'label' => 'Senha atual',
            'help' => 'Preencha com sua senha atual para confirmar que é você'
        ],
        'change_password' => 'Alterar senha',
        'send_password_reset_link' => 'Enviar link para redefinir senha',
        'password_successfully_reset' => 'Senha redefinida com sucesso',
        'edit_account' => 'Editar conta',
        'profile_saved' => 'Perfil atualizado com sucesso!',
        'welcome_to_demo_app_use_those_credentials' => 'Bem-vindo(a) à demonstração 2FAuth.<br><br>Você pode entrar utilizando o endereço de e-mail <strong>demo@2fauth.app</strong> e a senha <strong>demo</strong>',
        'welcome_to_testing_app_use_those_credentials' => 'Bem-vindo(a) à instância de teste 2FAuth.<br><br>Use o endereço de e-mail <strong>testing@2fauth.app</strong> e senha <strong>password</strong>',
        'register_punchline' => 'Bem-vindo(a) ao <b>2FAuth</b>.<br/>Você precisa de uma conta para ir mais longe, por favor, cadastre-se.',
        'reset_punchline' => '2FAuth enviará um link de redefinição de senha para este endereço. Clique no link no e-mail recebido para definir uma nova senha.',
        'name_this_device' => 'Nomear esse dispositivo',
        'delete_account' => 'Excluir conta',
        'delete_your_account' => 'Excluir sua conta',
        'delete_your_account_and_reset_all_data' => 'Sua conta de usuário será excluída, assim como todos os seus dados de 2FA. Não há volta.',
        'reset_your_password_to_delete_your_account' => 'Se você sempre usou o SSO para entrar, saia e use o recurso de redefinição. Ao receber uma senha, preencher esse formulário.',
        'deleting_2fauth_account_does_not_impact_provider' => 'Excluir a sua conta 2FAuth não tem impacto na sua conta SSO externa.',
        'user_account_successfully_deleted' => 'Conta excluída com sucesso',
        'has_lower_case' => 'Tem minúsculas',
        'has_upper_case' => 'Tem maiúsculas',
        'has_special_char' => 'Tem caractere especial',
        'has_number' => 'Tem número',
        'is_long_enough' => 'Mínimo 8 caracteres',
        'mandatory_rules' => 'Obrigatório',
        'optional_rules_you_should_follow' => 'Recomendação (alta)',
        'caps_lock_is_on' => 'Caps Lock está ativado',
    ],
    'sso_providers' => [
        'unknown' => 'desconhecido',
        'github' => 'Github',
        'openid' => 'OpenID'
    ]
];
