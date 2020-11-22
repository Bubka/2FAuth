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

    'resource_not_found' => 'Resource not found',
    'error_occured' => 'An error occured:',
    'already_one_user_registered' => 'There is already a registered user.',
    'cannot_register_more_user' => 'You cannot register more than one user.',
    'refresh' => 'Refresh',
    'no_valid_otp' => 'No valid OTP resource in this QR code',
    'something_wrong_with_server' => 'Something is wrong with your server',
    'Unable_to_decrypt_uri' => 'Unable to decrypt uri',
    'not_a_supported_otp_type' => 'This OTP format is not currently supported',
    'cannot_create_otp_without_secret' => 'Cannot create an OTP without a secret',
    'cannot_create_otp_with_those_parameters' => 'Cannot create an OTP with those parameters',
    'wrong_current_password' => 'Wrong current password, nothing has changed',
    'error_during_encryption' => 'Encryption failed, your database remains unprotected.',
    'error_during_decryption' => 'Decryption failed, your database is still protected. This is mainly caused by an integrity issue of encrypted data for one or more accounts.',
    'qrcode_cannot_be_read' => 'This QR code is unreadable',
];