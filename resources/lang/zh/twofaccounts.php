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

    'service' => '服务',
    'account' => '账户',
    'accounts' => '账户',
    'icon' => '图标',
    'icon_for_account_x_at_service_y' => '{account} 在 {service} 的帐户图标',
    'icon_to_illustrate_the_account' => '说明账户的图标',
    'remove_icon' => '移除图标',
    'no_account_here' => '这里没有两步验证！',
    'add_first_account' => '选择一个方法并添加您的第一个帐户',
    'use_full_form' => '或者使用完整的表单',
    'add_one' => '添加一个',
    'show_qrcode' => '显示 QR 码',
    'no_service' => '- 无服务 -',
    'account_created' => '帐户成功创建',
    'account_updated' => '账户成功更新',
    'accounts_deleted' => '帐户成功删除',
    'accounts_moved' => '帐户成功移动',
    'export_selected_to_json' => '将所选账号以json导出',
    'forms' => [
        'service' => [
            'placeholder' => '谷歌, 推特, 苹果',
        ],
        'account' => [
            'placeholder' => '李华',
        ],
        'new_account' => '新建账户',
        'edit_account' => '编辑账户',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => '扫描QR码',
        'upload_qrcode' => '上传一个QR码',
        'use_advanced_form' => '使用高级表单',
        'prefill_using_qrcode' => '使用QR码进行预填充',
        'use_qrcode' => [
            'val' => '使用一个QR码',
            'title' => '使用QR码来自动填充表单',
        ],
        'unlock' => [
            'val' => '解锁',
            'title' => '解锁它(风险自负)',
        ],
        'lock' => [
            'val' => '锁定',
            'title' => '将其锁定',
        ],
        'choose_image' => '上传',
        'i_m_lucky' => '手气不错',
        'i_m_lucky_legend' => '"手气不错"按钮会尝试获取指定服务的官方图标。输入实际的英文服务名（不带后缀）并避免输入错误。(测试中的功能)',
        'test' => '测试',
        'secret' => [
            'label' => '密钥',
            'help' => '用于生成安全码的密钥'
        ],
        'plain_text' => '纯文本',
        'otp_type' => [
            'label' => '选择要创建的 <abbr title="One-Time Password">OTP</abbr> 类型',
            'help' => '基于 时间的OTP(TOTP) 或 基于HMAC的OTP(HMAC-based OTP) 或 Steam OTP'
        ],
        'digits' => [
            'label' => '码长',
            'help' => '生成的安全码位数'
        ],
        'algorithm' => [
            'label' => '算法',
            'help' => '用于保护您的安全代码的算法'
        ],
        'period' => [
            'label' => '周期',
            'placeholder' => '默认为30',
            'help' => '生成的二维码的以秒为单位的有效期限'
        ],
        'counter' => [
            'label' => '计数器',
            'placeholder' => '默认为0',
            'help' => '初始计数器值',
            'help_lock' => '编辑计数器是危险的，因为您可能使帐户与服务的验证服务器失去同步。点击锁的图标可启用更改，但只应在您知道您在做什么时使用'
        ],
        'image' => [
            'label' => '图像',
            'placeholder' => 'http://...',
            'help' => '作为帐户图标的 URL'
        ],
        'options_help' => '如果您不知道如何填写，您可以将下列选项留空。将会应用最常见的设置。',
        'alternative_methods' => '备选方法',
    ],
    'stream' => [
        'live_scan_cant_start' => '扫描无法开始 :(',
        'need_grant_permission' => [
            'reason' => '2FAuth 没有权限访问您的相机',
            'solution' => '您需要授予权限才能使用您的设备相机。 如果您已经拒绝，且您的浏览器不会再次提示您，请参考浏览器文档以了解如何授予权限。'
        ],
        'not_readable' => [
            'reason' => '载入扫描仪失败',
            'solution' => '摄像头是否已在使用？请确保没有其他应用使用您的摄像头并重试'
        ],
        'no_cam_on_device' => [
            'reason' => '此设备上没有摄像头',
            'solution' => '也许你忘了插上你的摄像头'
        ],
        'secured_context_required' => [
            'reason' => '需要安全上下文',
            'solution' => '实时扫描需要HTTPS。如果您从计算机运行2FAuth，请不要使用localhost以外的虚拟主机'
        ],
        'https_required' => '摄像机需要 HTTPS',
        'camera_not_suitable' => [
            'reason' => '已安装的摄像头不合适。',
            'solution' => '请使用其他摄像头或更换设备'
        ],
        'stream_api_not_supported' => [
            'reason' => '此浏览器不支持 Stream API',
            'solution' => '您应该使用一个现代浏览器'
        ],
    ],
    'confirm' => [
        'delete' => '你确定要删除这个账户吗？',
        'cancel' => '帐户将丢失。您确定吗？',
        'discard' => '您确定要放弃此账户吗？',
        'discard_all' => '您确定要放弃所有账户吗？',
        'discard_duplicates' => '您确定要放弃所有重复账户吗？',
    ],
    'import' => [
        'import' => '导入',
        'to_import' => '导入',
        'import_legend' => '2FAuth 可以从各种2FA 应用程序导入数据。<br />使用这些应用的导出功能来获取迁移资源(QR码或文件)，并在下方加载它。',
        'upload' => '上传',
        'scan' => '扫描',
        'supported_formats_for_qrcode_upload' => '接受：jpg、jpeg、png、bmp、gif、svg或webp',
        'supported_formats_for_file_upload' => '接受：纯文本，json，2fas',
        'supported_migration_formats' => '支持的迁移格式',
        'qr_code' => '二维码',
        'plain_text' => '纯文本',
        'issuer' => '发行商',
        'imported' => '已导入',
        'failure' => '失败',
        'x_valid_accounts_found' => '找到 {count} 个有效账户',
        'import_all' => '全部导入',
        'import_this_account' => '导入此账户',
        'discard_all' => '全部丢弃',
        'discard_duplicates' => '丢弃重复项',
        'discard_this_account' => '丢弃此帐户',
        'generate_a_test_password' => '生成一个测试密码',
        'possible_duplicate' => '完全相同的帐户已经存在',
        'invalid_account' => '- 无效账户 -',
        'invalid_service' => '- 无效服务 -',
        'do_not_set_password_or_encryption' => '当您想要导入到2FAuth时不要启用密码保护或加密。',
    ],

];