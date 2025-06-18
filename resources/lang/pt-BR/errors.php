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

    'resource_not_found' => 'Recurso não encontrado',
    'error_occured' => 'Ocorreu um erro:',
    'refresh' => 'Atualizar',
    'no_valid_otp' => 'Nenhum OTP válido neste código QR',
    'something_wrong_with_server' => 'Algo está errado com o seu servidor',
    'Unable_to_decrypt_uri' => 'Não foi possível descriptografar a uri',
    'not_a_supported_otp_type' => 'Este formato OTP não é suportado no momento',
    'cannot_create_otp_without_secret' => 'Não é possível criar um OTP sem um segredo',
    'data_of_qrcode_is_not_valid_URI' => 'Os dados deste código QR não são um URI de Autenticação OTP válido. O código QR contém:',
    'wrong_current_password' => 'Senha atual incorreta, nada foi alterado',
    'error_during_encryption' => 'Falha na criptografia, seu banco de dados permanece desprotegido.',
    'error_during_decryption' => 'Falha na descriptografia, seu banco de dados ainda está protegido. Isso é causado principalmente por um problema de integridade de dados criptografados para uma ou mais contas.',
    'qrcode_cannot_be_read' => 'Este código QR é ilegível',
    'too_many_ids' => 'muitos IDs foram incluídos no parâmetro de consulta, é permitido o máximo 100',
    'delete_user_setting_only' => 'Apenas configuração criada pelo usuário pode ser excluída',
    'indecipherable' => '*indecifrável*',
    'cannot_decipher_secret' => 'O segredo não pode ser decifrado. Isso é causado principalmente por um APP_KEY errado definido no arquivo .env de configuração do 2Fauth, ou de dados corrompidos armazenados na base de dados.',
    'https_required' => 'Contexto HTTPS requerido',
    'browser_does_not_support_webauthn' => 'Seu dispositivo não suporta webauth. Tente novamente mais tarde usando um navegador mais moderno',
    'aborted_by_user' => 'Cancelado pelo usuário',
    'security_device_already_registered' => 'Dispositivo já registrado',
    'not_allowed_operation' => 'Operação não permitida',
    'no_authenticator_support_specified_algorithms' => 'Nenhum autenticador suporta algoritmos especificados',
    'authenticator_missing_discoverable_credential_support' => 'O autenticador não possui suporte de credenciais detectável',
    'authenticator_missing_user_verification_support' => 'Suporte à verificação de usuário faltando na autenticação',
    'unknown_error' => 'Erro desconhecido',
    'security_error_check_rpid' => 'Erro de segurança<br/>Verifique variável WEBAUTHN_ID em .env',
    '2fauth_has_not_a_valid_domain' => 'O domínio de 2FAuth não é um domínio válido',
    'user_id_not_between_1_64' => 'O ID do usuário não estava entre 1 e 64 caracteres',
    'no_entry_was_of_type_public_key' => 'Nenhuma entrada é do tipo "chave pública"',
    'unsupported_with_reverseproxy' => 'Não aplicável ao usar um proxy de autenticação ou SSO.',
    'unsupported_with_sso_only' => 'Este método de autenticação é apenas para administradores. Usuários devem fazer login com SSO.',
    'user_deletion_failed' => 'A exclusão da conta do usuário falhou, nenhum dado foi excluído',
    'auth_proxy_failed' => 'Falha na autenticação do proxy',
    'auth_proxy_failed_legend' => '2Fauth está configurado para ser executado atrás de um proxy autenticado, mas seu proxy não retorna o cabeçalho esperado. Verifique sua configuração e tente novamente.',
    'invalid_x_migration' => 'Dados de :appname inválidos ou ilegíveis',
    'invalid_2fa_data' => 'Dados 2FA inválidos',
    'unsupported_migration' => 'Os dados não correspondem a qualquer formato suportado',
    'unsupported_otp_type' => 'Tipo de OTP não suportado',
    'encrypted_migration' => 'Inlegível, os dados parecem criptografados',
    'no_icon_for_this_variant' => 'No icon available in this variant',
    'file_upload_failed' => 'Falha no upload do arquivo',
    'unauthorized' => 'Não autorizado',
    'unauthorized_legend' => 'Você não tem permissão para visualizar este recurso ou para executar esta ação',
    'cannot_delete_the_only_admin' => 'Não é possível excluir a única conta de administrador',
    'cannot_demote_the_only_admin' => 'Não é possível rebaixar a única conta de administrador',
    'error_during_data_fetching' => '💀 Algo deu errado durante a obtenção de dados',
    'check_failed_try_later' => 'Falha na busca, tente novamente mais tarde',
    'sso_disabled' => 'SSO está desativado',
    'sso_bad_provider_setup' => 'Este provedor SSO não está totalmente configurado no seu arquivo .env',
    'sso_failed' => 'Autenticação via SSO rejeitada',
    'sso_no_register' => 'Os registros estão desativados',
    'sso_email_already_used' => 'Uma conta de usuário com o mesmo endereço de e-mail já existe, mas não corresponde ao seu ID de conta externa. Não use SSO se você já estiver registrado no 2FAuth com este e-mail.',
    'account_managed_by_external_provider' => 'Conta gerenciada por um provedor externo',
    'data_cannot_be_refreshed_from_server' => 'Os dados não podem ser atualizados a partir do servidor',
    'no_pwd_reset_for_this_user_type' => 'Redefinição de senha indisponível para este usuário',
    'cannot_detect_qrcode_in_image' => 'Não é possível detectar um código QR na imagem, tente recortar a imagem',
    'cannot_decode_detected_qrcode' => 'Não é possível decodificar o código QR detectado, tentar cortar ou desembaçar a imagem',
    'qrcode_has_invalid_checksum' => 'QR code possui checksum inválido',
    'no_readable_qrcode' => 'Nenhum código QR legível',
    'failed_icon_store_database_toggling' => 'Houve um erro ao migrar os ícones. A configuração foi restaurada para o valor anterior.',
    'failed_to_retrieve_app_settings' => 'Falha ao obter as configurações do sistema',
    'reserved_name_please_choose_something_else' => 'Esse nome não pode ser usado, selecione outro nome',
];