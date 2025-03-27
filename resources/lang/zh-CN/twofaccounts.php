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
    'account' => '账号',
    'icon' => '图标',
    'icon_to_illustrate_the_account' => '账号图标',
    'remove_icon' => '移除图标',
    'no_account_here' => '无任何 2FA 账号！',
    'add_first_account' => '选择一个添加方式，创建您的第一个账号',
    'use_full_form' => '或通过填写表单创建',
    'add_one' => '添加一个',
    'show_qrcode' => '显示二维码',
    'no_service' => '- 无服务 -',
    'account_created' => '账号创建成功',
    'account_updated' => '账号更新成功',
    'accounts_deleted' => '账户删除成功',
    'accounts_moved' => '账号移动成功',
    'export_selected_accounts' => '导出选中的账户',
    'twofauth_export_format' => '2FAuth 格式',
    'twofauth_export_format_sub' => '以 2FAuth json 结构导出数据',
    'twofauth_export_format_desc' => '如果您的目的是创建一个备份，以备随时恢复数据，建议您优先使用此选项。这个格式可以保存图标信息。',
    'twofauth_export_format_url' => '结构定义描述：',
    'twofauth_export_schema' => '2FAuth 导出结构',
    'otpauth_export_format' => 'otpauth URI',
    'otpauth_export_format_sub' => '以 optauth URI 列表结构导出数据',
    'otpauth_export_format_desc' => 'otpauth URI 是用于传输 2FA 数据的最常用格式。例如，当您在网站上启用 2FA 时，显示的二维码就包含 otpauth URI 数据。点击即可从 2FAuth 切换至此选项。',
    'reveal' => '显示',
    'forms' => [
        'service' => [
            'placeholder' => '服务名称',
        ],
        'account' => [
            'placeholder' => '账号名称',
        ],
        'new_account' => '新建账号',
        'edit_account' => '编辑账号',
        'otp_uri' => 'OTP Uri',
        'scan_qrcode' => '扫描二维码',
        'upload_qrcode' => '上传二维码',
        'use_advanced_form' => '手动填写',
        'prefill_using_qrcode' => '使用二维码来填写',
        'use_qrcode' => [
            'val' => '使用二维码',
            'title' => '扫描二维码，自动填写表单',
        ],
        'unlock' => [
            'val' => '解锁',
            'title' => '解锁 (注意安全风险)',
        ],
        'lock' => [
            'val' => '锁定',
            'title' => '锁定',
        ],
        'choose_image' => '上传',
        'i_m_lucky' => '手气不错',
        'i_m_lucky_legend' => '“手气不错” 功能可以尝试获取此服务的官方图标。若要提高成功率，请在 “服务名称” 栏中以英文输入服务名。(测试中的功能)',
        'test' => '测试',
        'group' => [
            'label' => '分组',
            'help' => '此账户要移动到的组'
        ],
        'secret' => [
            'label' => '密钥',
            'help' => '用于生成安全码的密钥'
        ],
        'plain_text' => '纯文本',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => 'TOTP，HOTP 或 Steam OTP'
        ],
        'digits' => [
            'label' => '码长',
            'help' => '生成的验证码位数'
        ],
        'algorithm' => [
            'label' => '算法',
            'help' => '验证码的加密算法'
        ],
        'period' => [
            'label' => '周期',
            'placeholder' => '默认为 30',
            'help' => '验证码的有效期 (秒)'
        ],
        'counter' => [
            'label' => '计数器',
            'placeholder' => '默认为 0',
            'help' => '计数器的初始值',
            'help_lock' => '如果计数器设置错误，可能会导致验证码失效。如果您不了解此功能，请勿随意编辑。您可以点击 “锁定” 图标来解锁编辑，但操作时请务必小心。'
        ],
        'image' => [
            'label' => '图像',
            'placeholder' => 'http://...',
            'help' => '用作账号图标的图像 URL 地址'
        ],
        'options_help' => '如果您不了解下列选项，请将对应选项留空来使用默认配置。',
        'alternative_methods' => '其他创建方式',
        'spaces_are_ignored' => '无用的空格将被自动删除'
    ],
    'stream' => [
        'live_scan_cant_start' => '无法启动扫描 :(',
        'need_grant_permission' => [
            'reason' => '2FAuth 没有权限访问您的相机',
            'solution' => '2FAuth 需要您的授权才能使用此设备上的相机。如果您已点击过 “拒绝”，且您的浏览器没有再次提示您进行授权，请查找浏览器的文档以了解如何重新授权。',
            'click_camera_icon' => '通常情况下，您可以点击浏览器地址栏中 (或旁边) 的相机图标来继续。',
        ],
        'not_readable' => [
            'reason' => '扫描启动失败',
            'solution' => '摄像头是否已被占用？请确保没有其他应用正在使用您的摄像头，并再试一次'
        ],
        'no_cam_on_device' => [
            'reason' => '此设备上没有摄像头',
            'solution' => '或许您忘了连接摄像头'
        ],
        'secured_context_required' => [
            'reason' => '需要 secure 字段',
            'solution' => '扫描需要通过 HTTPS 协议通信。如果您是在电脑上运行 2FAuth 程序，请切换至本地主机，不要使用其他虚拟主机'
        ],
        'https_required' => '摄像机需要 HTTPS',
        'camera_not_suitable' => [
            'reason' => '已安装的摄像头不适用',
            'solution' => '请使用其他摄像头或更换设备'
        ],
        'stream_api_not_supported' => [
            'reason' => '此浏览器不支持 Stream API',
            'solution' => '请换用新版浏览器'
        ],
    ],
    'confirm' => [
        'delete' => '您确定要删除此账号吗？',
        'cancel' => '将放弃所有更改，确定要继续吗？',
        'discard' => '您确定要丢弃此账号吗？',
        'discard_all' => '您确定要丢弃所有账号吗？',
        'discard_duplicates' => '您确定要丢弃所有重复的账号吗？',
    ],
    'import' => [
        'import' => '导入',
        'to_import' => '导入',
        'import_legend' => '2FAuth 支持从各类 2FA 应用导入数据。',
        'import_legend_afterpart' => '使用这些应用的 “导出” 功能来获取迁移资源，例如二维码或 JSON 文件，然后在 2FAuth 中导入。',
        'upload' => '上传',
        'scan' => '扫描',
        'supported_formats_for_qrcode_upload' => '支持格式：jpg、jpeg、png、bmp、gif、svg 或 webp',
        'supported_formats_for_file_upload' => '支持格式：json、2fas 或纯文本',
        'expected_format_for_direct_input' => '请填入一个包含 otpauth URI 的列表，每行一条',
        'supported_migration_formats' => '支持的迁移格式',
        'qr_code' => '二维码',
        'text_file' => '文本文件',
        'direct_input' => '直接输入',
        'plain_text' => '纯文本',
        'parsing_data' => '正在解析数据…',
        'issuer' => '签发方',
        'imported' => '已导入',
        'failure' => '失败',
        'x_valid_accounts_found' => '找到 :count 个有效账号',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => '在导入的数据中找到了下列 2FA 账号，且没有被添加到 2FAuth 过。',
        'use_buttons_to_save_or_discard' => '点击对应的按钮，即可选择丢弃这些账号，或将其保存到您的 2FA 列表中。',
        'import_all' => '全部导入',
        'import_this_account' => '导入此账号',
        'discard_all' => '全部丢弃',
        'discard_duplicates' => '丢弃重复项',
        'discard_this_account' => '丢弃此账号',
        'generate_a_test_password' => '生成测试密码',
        'possible_duplicate' => '已存在相同数据的账号',
        'invalid_account' => '- 无效账号 -',
        'invalid_service' => '- 无效服务 -',
        'do_not_set_password_or_encryption' => '如果您需要从其他 2FA 应用导出数据到 2FAuth，请务必在导出前关闭加密保护，否则 2FAuth 将无法解密数据。',
    ],

];