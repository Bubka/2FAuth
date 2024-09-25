<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'admin' => 'Administrador',
    'app_setup' => 'Configuração da Aplicação',
    'auth' => 'Auth',
    'registrations' => 'Registos',
    'users' => 'Usuários',
    'users_legend' => 'Gerenciar usuários registrados ou criar  em sua instância.',
    'admin_settings' => 'Configurações de administração',
    'create_new_user' => 'Criar usuário',
    'new_user' => 'Novo usuário',
    'search_user_placeholder' => 'Nome de usuário, e-mail...',
    'quick_filters_colons' => 'Filtro rápido:',
    'user_created' => 'usuário criado com sucesso',
    'confirm' => [
        'delete_user' => 'Tem certeza de que deseja excluir este usuário? Não há a opção de \'desfazer\'.',
        'request_password_reset' => 'Você tem certeza que deseja redefinir a senha deste usuário?',
        'purge_password_reset_request' => 'Tem certeza de que deseja revogar a solicitação anterior?',
        'delete_account' => 'Tem certeza de que deseja excluir este usuário?',
        'edit_own_account' => 'Esta é a sua própria conta. Tem certeza?',
        'change_admin_role' => 'Isto terá sérios impactos nas permissões deste usuário. Tem certeza?',
        'demote_own_account' => 'Você não será mais um administrador. Deseja realmente continuar?'
    ],
    'logs' => 'Registros',
    'administration_legend' => 'As seguintes configurações são globais e aplicam-se a todos os usuários.',
    'user_management' => 'Gerenciamento de usuários',
    'oauth_provider' => 'Provedor OAuth',
    'account_bound_to_x_via_oauth' => 'Esta conta está vinculada a uma conta :provedor via OAuth',
    'last_seen_on_date' => 'Visto por último :date',
    'registered_on_date' => 'Registrado em :date',
    'updated_on_date' => 'Atualizado em :date',
    'access' => 'Acesso',
    'password_requested_on_t' => 'Uma solicitação de redefinição de senha existe para este usuário (solicitação enviada em :datetime), o que significa que o usuário ainda não alterou sua senha, mas o link que recebeu ainda é válido. Esta pode ser uma requisição do próprio usuário ou de um administrador.',
    'password_request_expired' => 'Uma solicitação de redefinição de senha existe para este usuário, mas expirou, o que significa que o usuário não alterou sua senha a tempo. Esta pode ser uma requisição do próprio usuário ou de um administrador.',
    'resend_email' => 'Reenviar e-mail',
    'resend_email_title' => 'Reenviar um e-mail de redefinição de senha ao usuário',
    'resend_email_help' => 'Use <b>Reenviar e-mail</b> para enviar um novo e-mail de redefinição de senha para o usuário para ele definir uma nova senha. Isso deixará sua senha atual como está e qualquer pedido anterior será revogado.',
    'reset_password' => 'Redefinir senha',
    'reset_password_help' => 'Use <b>Redefinir senha</b> para forçar uma redefinição de senha (isso irá definir uma senha temporária) antes de enviar um e-mail de redefinição de senha para o usuário para que eles possam definir uma nova senha. Qualquer solicitação anterior será revogada.',
    'reset_password_title' => 'Redefinir a senha do usuário',
    'password_successfully_reset' => 'Senha redefinida com sucesso',
    'user_has_x_active_pat' => ':count token(s) ativo(s)',
    'user_has_x_security_devices' => ':count dispositivo(s) de segurança (chaves)',
    'revoke_all_pat_for_user' => 'Revogar o token de todos os usuários',
    'revoke_all_devices_for_user' => 'Revogar os dispositivos de segurança de todos os usuários',
    'danger_zone' => 'Zona de perigo',
    'delete_this_user_legend' => 'A conta de usuário será excluída assim como todos os seus dados de 2FA.',
    'this_is_not_soft_delete' => 'Não se trata de uma exclusão suave, não se pode retroceder.',
    'delete_this_user' => 'Excluir este usuário',
    'user_role_updated' => 'Função do usuário atualizada',
    'pats_succesfully_revoked' => 'PATs do usuário revogado com sucesso',
    'security_devices_succesfully_revoked' => 'Dispositivos de segurança do usuário revogado com sucesso',
    'variables' => 'Variáveis',
    'cache_cleared' => 'Cache apagado',
    'cache_optimized' => 'Cache otimizado',
    'check_now' => 'Verificar agora',
    'view_on_github' => 'Ver no GitHub',
    'x_is_available' => ':version está disponível',
    'successful_login_on' => 'Login bem-sucedido em <span class="light-or-darker">:login_at</span>',
    'successful_logout_on' => 'Logout bem-sucedido em <span class="light-or-darker">:login_at</span>',
    'failed_login_on' => 'Falha no login em <span class="light-or-darker">:login_at</span>',
    'viewed_on' => 'Visualizado em <span class="light-or-darker">:login_at</span>',
    'last_accesses' => 'Últimos acessos',
    'see_full_log' => 'Ver registro completo',
    'browser_on_platform' => ':browser em :platform',
    'access_log_has_more_entries' => 'O log de acesso contém mais entradas.',
    'access_log_legend_for_user' => 'Registro de acesso completo para o usuário :username',
    'show_last_month_log' => 'Mostrar entradas do último mês',
    'show_three_months_log' => 'Mostrar entradas dos últimos 3 meses',
    'show_six_months_log' => 'Mostrar entradas dos últimos 6 meses',
    'show_one_year_log' => 'Mostrar entradas do último ano',
    'sort_by_date_asc' => 'Mostrar o mais antigo primeiro',
    'sort_by_date_desc' => 'Mostrar o mais recente primeiro',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'forms' => [
        'use_encryption' => [
            'label' => 'Proteja dados confidenciais',
            'help' => 'Dados confidenciais, segredos e e-mails 2FA são armazenados criptografados no banco de dados. Certifique-se de salvar o valor APP_KEY do seu arquivo .env (ou do arquivo inteiro), pois ele serve como chave de criptografia. Não há como descriptografar dados criptografados sem essa chave.',
        ],
        'restrict_registration' => [
            'label' => 'Restringir registro',
            'help' => 'Tornar o registro disponível apenas para um intervalo limitado de endereços de e-mail. Ambas as regras podem ser usadas simultaneamente. Isso não tem efeito sobre o registro via SSO.',
        ],
        'restrict_list' => [
            'label' => 'Listas de filtros',
            'help' => 'Os endereços de e-mail nesta lista serão autorizados para registro. Endereços separados com o caractere "|"',
        ],
        'restrict_rule' => [
            'label' => 'Filtrando regras',
            'help' => 'E-mails que correspondem a esta expressão regular terá permissão para registrar',
        ],
        'disable_registration' => [
            'label' => 'Desabilitar registo',
            'help' => 'Impedir novos registros. A menos que sobrescrito (veja abaixo), isto afeta SSO também, então novos usuários não serão capazes de entrar via SSO',
        ],
        'enable_sso' => [
            'label' => 'Enable SSO',
            'help' => 'Permitir que os visitantes autentiquem usando um ID externo por meio do esquema de logon SSO',
        ],
        'use_sso_only' => [
            'label' => 'Use SSO only',
            'help' => 'Make SSO the only available method to log in to 2FAuth. Password login and Webauthn are then disabled for regular users. Administrators are not affected by this restriction.',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'Mantenha o registro SSO ativado',
            'help' => 'Permite que novos usuários façam login pela primeira vez via SSO enquanto os registros estão desativados',
        ],
        'is_admin' => [
            'label' => 'É administrador',
            'help' => 'Concede direitos de administrador ao usuário. Os administradores podem gerenciar o aplicativo, ou seja, modificar suas configurações e gerenciar seus usuários. Um administrador não tem como visualizar os dados 2FA de outro usuário ou gerar códigos para eles.'
        ],
        'test_email' => [
            'label' => 'Teste de configuração de e-mail',
            'help' => 'Envie um e-mail de teste para verificar a capacidade da sua instância de usar e-mail. É importante ter uma configuração funcional, caso contrário os usuários não poderão solicitar redefinição de senha, por exemplo.',
            'email_will_be_send_to_x' => 'O e-mail será enviado para <span class="is-family-code has-text-info">:email</span>',
        ],
        'cache_management' => [
            'label' => 'Gerenciamento de Cache',
            'help' => 'Às vezes, o cache precisa ser limpo, por exemplo, após uma alteração de variáveis de ambiente ou de uma atualização. Você pode fazer isso a partir daqui.',
        ]
    ],

];