<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notifications Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'hello' => 'Olá',
    'hello_user' => 'Olá :username,',
    'regards' => 'Atenciosamente',
    'test_email_settings' => [
        'subject' => 'E-mail de teste 2FAuth',
        'reason' => 'Você está recebendo este e-mail porque você solicitou um e-mail de teste para validar as configurações de e-mail da sua instância 2FAuth.',
        'success' => 'Boas notícias, funcionou :)'
    ],
    'new_device' => [
        'subject' => 'Conexão com o 2FAuth de um novo dispositivo',
        'resume' => 'Um novo dispositivo acabou de se conectar à sua conta 2FAuth.',
        'connection_details' => 'Aqui estão os detalhes desta conexão',
        'recommandations' => 'Se foi você, você pode ignorar este alerta. Se suspeitar de qualquer atividade suspeita em sua conta, altere sua senha imediatamente.'
    ],
    'failed_login' => [
        'subject' => 'Falha no login para 2FAuth',
        'resume' => 'Houve uma tentativa de login falhada em sua conta 2FAuth.',
        'connection_details' => 'Aqui estão os detalhes desta tentativa de conexão',
        'recommandations' => 'Se foi você, pode ignorar este alerta. Se outras tentativas falharem, você deve entrar em contato com o administrador do 2FAuth para rever as configurações de segurança e tomar ações contra este atacante.'
    ],
];