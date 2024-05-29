/*! 2FAuth version 5.2.0 - Copyright (c) 2024 Bubka - https://github.com/Bubka/2FAuth */const t={"admin.admin":"管理员","admin.app_setup":"应用设置","admin.registrations":"注册","admin.users":"用户","admin.users_legend":"管理在您的实例上注册的用户或创建新的用户。","admin.admin_settings":"管理员设置","admin.create_new_user":"创建新用户","admin.new_user":"新用户","admin.search_user_placeholder":"用户名，电子邮件...","admin.quick_filters_colons":"快速筛选:","admin.user_created":"用户创建成功","admin.confirm.delete_user":"您确定要删除这个用户吗？没有回头路。","admin.confirm.request_password_reset":"您确定要重置此用户的密码吗？","admin.confirm.purge_password_reset_request":"Are you sure you want to revoke the previous request?","admin.confirm.delete_account":"您确定要删除该用户吗？","admin.confirm.edit_own_account":"这是您自己的帐户。您确定吗？","admin.confirm.change_admin_role":"这将会对此用户的权限产生重大影响。您确定吗？","admin.confirm.demote_own_account":"您将不再是管理员。真的确定吗？","admin.logs":"日志","admin.administration_legend":"以下设置是全局设置，适用于所有用户。","admin.user_management":"用户管理","admin.oauth_provider":"OAuth 提供者","admin.account_bound_to_x_via_oauth":"此帐户通过 OAuth 绑定到 :provider 帐户","admin.last_seen_on_date":"最后活跃：:date","admin.registered_on_date":"注册于 :date","admin.updated_on_date":"更新于 :date","admin.access":"访问","admin.password_requested_on_t":"A password reset request exists for this user (request sent at :datetime), which means that the user has not yet changed their password but the link they received is still valid. This may be a request from the user themselves or from an administrator.","admin.password_request_expired":"A password reset request exists for this user but has expired, meaning that the user has not changed their password in time. This may be a request from the user themselves or from an administrator.","admin.resend_email":"重新发送电子邮件","admin.resend_email_title":"重新发送密码重置邮件给用户","admin.resend_email_help":"使用 <b>重新发送电子邮件</b> 向用户发送新密码重置邮件，以便他可以设置新密码。 这将保留当前密码，且之前的请求都将被撤销。","admin.reset_password":"重置密码","admin.reset_password_help":"Use <b>Reset password</b> to force a password reset (this will set a temporary password) before sending a password reset email to the user so they can set a new password. Any previous request will be revoked.","admin.reset_password_title":"重置用户的密码","admin.password_successfully_reset":"密码重置成功","admin.user_has_x_active_pat":":count 个有效的令牌","admin.user_has_x_security_devices":":count 个安全设备 (安全钥匙)","admin.revoke_all_pat_for_user":"吊销用户的所有令牌","admin.revoke_all_devices_for_user":"吊销用户的所有安全设备","admin.danger_zone":"危险选项","admin.delete_this_user_legend":"用户帐户及其所有2FA 数据将被删除。","admin.this_is_not_soft_delete":"这不是软删除，没有退路。","admin.delete_this_user":"删除这个用户","admin.user_role_updated":"用户角色已更新","admin.pats_succesfully_revoked":"用户的令牌已成功吊销。","admin.security_devices_succesfully_revoked":"用户的安全设备已成功吊销。","admin.variables":"变量","admin.cache_cleared":"已清除缓存","admin.cache_optimized":"已优化缓存","admin.check_now":"立即检查","admin.view_on_github":"在 GitHub 上查看","admin.x_is_available":"新版本 :version 可用！","admin.successful_login_on":'Successful login on <span class="light-or-darker">:login_at</span>',"admin.successful_logout_on":'Successful logout on <span class="light-or-darker">:login_at</span>',"admin.failed_login_on":'Failed login on <span class="light-or-darker">:login_at</span>',"admin.viewed_on":'Viewed on <span class="light-or-darker">:login_at</span>',"admin.last_accesses":"Last accesses","admin.see_full_log":"See full log","admin.browser_on_platform":":browser on :platform","admin.access_log_has_more_entries":"The access log contains more entries.","admin.access_log_legend_for_user":"Full access log for user :username","admin.show_last_month_log":"Show entries from the last month","admin.show_three_months_log":"Show entries from the last 3 months","admin.show_six_months_log":"Show entries from the last 6 months","admin.show_one_year_log":"Show entries from the last year","admin.sort_by_date_asc":"Show least recent first","admin.sort_by_date_desc":"Show most recent first","admin.forms.use_encryption.label":"保护敏感数据","admin.forms.use_encryption.help":"敏感数据、2FA 秘钥和电子邮件会被加密存储在数据库中。请务必备份您在 .env 中设置的 APP_KEY 的值(或备份整个文件)。没有此密钥将无法解码已加密的数据。","admin.forms.restrict_registration.label":"限制注册","admin.forms.restrict_registration.help":"只允许有限范围的电子邮件地址进行注册。这两条规则都可以同时使用。这对通过SSO进行注册没有影响。","admin.forms.restrict_list.label":"过滤列表","admin.forms.restrict_list.help":'此列表中的电子邮件将被允许注册。用管道分隔("|")',"admin.forms.restrict_rule.label":"过滤规则","admin.forms.restrict_rule.help":"与此正则表达式匹配的电子邮件将被允许注册","admin.forms.disable_registration.label":"禁用注册","admin.forms.disable_registration.help":"防止新用户注册。除非被覆盖(见下文)，否则这也会影响到SSO，所以新用户将无法通过 SSO 登录","admin.forms.enable_sso.label":"启用单点登录 (SSO)","admin.forms.enable_sso.help":"允许访问者通过单点登录方案使用外部ID进行身份验证","admin.forms.keep_sso_registration_enabled.label":"保持启用 SSO 注册","admin.forms.keep_sso_registration_enabled.help":"在注册已禁用时允许新用户通过 SSO 登录","admin.forms.is_admin.label":"管理员","admin.forms.is_admin.help":"授予用户管理员权限。管理员有权管理整个应用，如: 设置和其他用户，但不能生成不属于他们的2FA 密码。","admin.forms.test_email.label":"电子邮件配置测试","admin.forms.test_email.help":"发送测试邮件来控制您的实例的电子邮件配置。 有一个正常的工作配置是很必要的，否则用户将无法请求重置密码。","admin.forms.test_email.email_will_be_send_to_x":'电子邮件将被发送到 <span class="is-family-code has-text-info">:email</span>',"admin.forms.cache_management.label":"缓存管理","admin.forms.cache_management.help":"有时缓存需要清除，例如在更改环境变量或更新后。您可以在此处这样做。","auth.failed":"用户名或密码错误","auth.password":"提供的密码不正确","auth.throttle":"您尝试的登录次数过多，请 :seconds 秒后再试。","auth.sign_out":"登出","auth.sign_in":"登录","auth.sign_in_using":"登录使用","auth.or_continue_with":"You can also continue with:","auth.sign_in_using_security_device":"使用安全设备登录","auth.login_and_password":"用户名和密码","auth.register":"注册","auth.welcome_to_2fauth":"欢迎使用 2FAuth","auth.autolock_triggered":"已自动锁定","auth.autolock_triggered_punchline":"自动锁定已触发。您已被自动断开连接。","auth.already_authenticated":"已验证","auth.authentication":"身份认证","auth.maybe_later":"以后再说","auth.user_account_controlled_by_proxy":"用户帐户由身份验证代理提供。<br />请在代理中管理帐户。","auth.auth_handled_by_proxy":"身份验证由代理处理，下面的设置被禁用。<br />在代理管理身份验证。","auth.confirm.logout":"您确定要注销吗？","auth.confirm.revoke_device":"你确定要删除此设备？","auth.confirm.delete_account":"您确定要删除您的账户?","auth.webauthn.security_device":"硬件安全密钥","auth.webauthn.security_devices":"安全设备","auth.webauthn.security_devices_legend":"您可以用来登录2FAuth的认证设备，例如安全密钥(如Yubikey)或具有生物识别能力的智能手机(如Apple Face Id/TouchId)","auth.webauthn.enhance_security_using_webauthn":`您可以通过启用 WebAuthn 身份验证来增强您的2FAuth 账户的安全性。<br /><br />
WebAuthn允许您使用受信任的设备 (如Yubikeys 或具有生物识别能力的智能手机) 快速和更安全地登录。`,"auth.webauthn.use_security_device_to_sign_in":"准备好使用您的（一个）安全设备进行身份验证。请插入您的密钥，移除口罩或手套等。","auth.webauthn.lost_your_device":"设备丢失？","auth.webauthn.recover_your_account":"恢复您的账号","auth.webauthn.account_recovery":"恢复账号","auth.webauthn.recovery_punchline":"2FAuth 将向您发送恢复链接到此电子邮件地址。点击收到电子邮件中的链接注册新的安全设备。<br /><br />确保在您可以在自己的设备上打开电子邮件。","auth.webauthn.send_recovery_link":"发送恢复链接","auth.webauthn.account_recovery_email_sent":"账号恢复邮件已发送！","auth.webauthn.disable_all_security_devices":"禁用所有安全设备","auth.webauthn.disable_all_security_devices_help":"您的所有安全设备都将被撤销。如果您丢失了一个设备或其安全性已经受到损害，请使用此选项。","auth.webauthn.register_a_new_device":"注册新设备","auth.webauthn.register_a_device":"注册设备","auth.webauthn.device_successfully_registered":"成功注册设备","auth.webauthn.device_revoked":"成功注销设备","auth.webauthn.revoking_a_device_is_permanent":"注销设备是永久性的","auth.webauthn.recover_account_instructions":"若要恢复您的帐户，2FAuth 将会重置一些Webauth设置，以便您可以使用您的电子邮件和密码登录。","auth.webauthn.invalid_recovery_token":"无效的恢复密钥","auth.webauthn.webauthn_login_disabled":"Webauthn 登录已被禁用","auth.webauthn.invalid_reset_token":"此密码重置令牌无效","auth.webauthn.rename_device":"重命名设备","auth.webauthn.my_device":"我的设备","auth.webauthn.unknown_device":"未知设备","auth.webauthn.use_webauthn_only.label":"仅使用 WebAuthn","auth.webauthn.use_webauthn_only.help":`将WebAuthn设定为登录2FAuth账户的唯一授权的登录方式。推荐启用此选项，并利用WebAuth增强安全性。<br /><br />
                设备丢失时， 您可以通过重置此选项并使用您的电子邮件和密码登录来恢复您的帐户。<br /><br />
                请注意！ 尽管启用了此选项，输入电子邮件和密码的登录界面仍然可用，但是会提示 “身份验证失败”。`,"auth.webauthn.need_a_security_device_to_enable_options":"设置至少一个WebAuth设备以启用以下选项","auth.webauthn.options":"选项","auth.forms.name":"用户名","auth.forms.login":"登录","auth.forms.webauthn_login":"使用 WebAuthn 登录","auth.forms.email":"邮箱","auth.forms.password":"密码","auth.forms.reveal_password":"显示密码","auth.forms.hide_password":"隐藏密码","auth.forms.confirm_password":"确认密码","auth.forms.new_password":"New password","auth.forms.confirm_new_password":"确认新密码","auth.forms.dont_have_account_yet":"还没有账号？","auth.forms.already_register":"已经注册？","auth.forms.authentication_failed":"验证失败","auth.forms.forgot_your_password":"忘记密码？","auth.forms.request_password_reset":"重置密码","auth.forms.reset_your_password":"重置你的密码","auth.forms.reset_password":"重置密码","auth.forms.disabled_in_demo":"此功能将在演示模式下禁用。","auth.forms.current_password.label":"当前密码","auth.forms.current_password.help":"填写您当前设置的密码以确认是您本人","auth.forms.change_password":"修改密码","auth.forms.send_password_reset_link":"发送密码重置链接","auth.forms.password_successfully_reset":"密码重置成功。","auth.forms.edit_account":"编辑账户","auth.forms.profile_saved":"个人资料更新成功！","auth.forms.welcome_to_demo_app_use_those_credentials":"欢迎访问 2FAuth 的演示站点。<br><br>您可以使用电子邮件地址 <strong>demo@2fauth.app</strong> 和密码 <strong>demo</strong>来登录。","auth.forms.welcome_to_testing_app_use_those_credentials":"欢迎访问 2FAuth 的测试实例。<br><br>您可以使用电子邮件地址 <strong>testing@2fauth.app</strong> 和密码 <strong>password</strong>来登录。","auth.forms.register_punchline":"欢迎使用 <b>2FAuth</b>。<br/>您需要一个账号才能继续，请先完成注册。","auth.forms.reset_punchline":"2FAuth 将向您发送密码重置链接到此邮箱。请点击收到的电子邮件中的链接设置新密码。","auth.forms.name_this_device":"命名此设备","auth.forms.delete_account":"删除账户","auth.forms.delete_your_account":"删除您的账户","auth.forms.delete_your_account_and_reset_all_data":"这将重置您的 2FAuth。您的账号以及所有的 2FA 数据都将被删除，这是一个不可逆的操作。","auth.forms.reset_your_password_to_delete_your_account":"如果您总是使用 SSO 登录， 登出后使用重置密码功能获取密码，以便您可以填写此表格。","auth.forms.deleting_2fauth_account_does_not_impact_provider":"删除您的 2FAuth 帐户对您的外部 SSO 帐户没有影响。","auth.forms.user_account_successfully_deleted":"账号已成功删除","auth.forms.has_lower_case":"包含小写字母","auth.forms.has_upper_case":"包含大写字母","auth.forms.has_special_char":"包含特殊字符","auth.forms.has_number":"包含数字","auth.forms.is_long_enough":"至少 8 个字符","auth.forms.mandatory_rules":"必选项","auth.forms.optional_rules_you_should_follow":"建议（推荐）","auth.forms.caps_lock_is_on":"大写锁定已开启","commons.cancel":"取消","commons.update":"更新","commons.copy":"复制","commons.copy_to_clipboard":"复制到剪贴板","commons.copied_to_clipboard":"已复制到剪贴板","commons.profile":"个人资料","commons.edit":"编辑","commons.delete":"删除","commons.disable":"禁用","commons.enable":"启用","commons.create":"创建","commons.save":"保存","commons.close":"关闭","commons.clear":"清空","commons.clear_search":"清除搜索结果","commons.demo_do_not_post_sensitive_data":"这是一个演示应用，请不要上传任何敏感数据","commons.testing_do_not_post_sensitive_data":"这是一个测试应用，请不要上传任何敏感数据","commons.x_selected":"已选择:count个","commons.name":"名称","commons.manage":"管理","commons.done":"完成","commons.new":"新建","commons.back":"返回","commons.move":"移动","commons.export":"导出","commons.all":"全部","commons.check_all":"全选","commons.select_all":"全选","commons.clear_selection":"清除选择","commons.sort_descending":"降序排列","commons.sort_ascending":"升序排序","commons.rename":"重命名","commons.new_name":"新名称","commons.options":"选项","commons.reload":"刷新","commons.refresh":"刷新","commons.data_refreshed_to_reflect_server_changes":"数据已被刷新以反映服务器侧的更改","commons.generate":"生成","commons.generating_otp":"正在生成 OTP","commons.open_in_browser":"在浏览器中打开","commons.continue":"继续","commons.discard":"放弃","commons.about":"关于","commons.usefull_links":"有用的链接","commons.environment":"环境","commons.credits":"鸣谢","commons.2fauth_teaser":"用于管理您的两步验证 (2FA) 帐户并生成安全码的网页应用","commons.made_with":"基于","commons.ui_icons_by":"UI 图标来自","commons.logos_by":"Logo 来自","commons.search":"搜索​​​​","commons.resources":"资源","commons.check_for_update":"检查更新","commons.check_for_update_help":"自动检查 (每周一次) 并当 2FAuth 在 Github 上发布新版本时发出提醒","commons.you_are_up_to_date":"该实例是最新的","commons.2fauth_description":"用于管理您的两步验证 (2FA) 账户并生成安全码的网页应用","commons.image_of_qrcode_to_scan":"要扫描的二维码图像","commons.file":"文件","commons.or":"或","commons.close_the_x_page":"关闭 {pagetitle} 页","commons.submit":"提交","commons.default":"默认值","commons.back_to_home":"返回首页","commons.nothing":"无","commons.no_result":"无结果","commons.information":"信息","commons.send":"发送","commons.optimize":"优化","commons.IP":"IP地址","commons.browser":"浏览器","commons.operating_system_short":"系统版本","commons.no_entry_yet":"暂无记录","commons.time":"时间","commons.ip_address":"IP 地址","commons.device":"设备","commons.one_month":"一个月","commons.x_month":":x mos.","commons.one_year":"1 yr.","errors.resource_not_found":"资源未找到","errors.error_occured":"发生错误:","errors.refresh":"刷新","errors.no_valid_otp":"此二维码中没有有效的OTP资源","errors.something_wrong_with_server":"服务器发生内部错误","errors.Unable_to_decrypt_uri":"无法解密uri","errors.not_a_supported_otp_type":"不支持此OTP格式","errors.cannot_create_otp_without_secret":"无法在没有密码的情况下创建一个OTP","errors.data_of_qrcode_is_not_valid_URI":"此QR码的数据不是有效的OTP Auth URI。该QR码包含:","errors.wrong_current_password":"当前密码错误，没有发生任何更改","errors.error_during_encryption":"加密失败，您的数据库仍未受到保护","errors.error_during_decryption":"解密失败，您的数据库仍受保护。这通常由一个或多个帐户加密数据的完整性不足导致。","errors.qrcode_cannot_be_read":"二维码无效","errors.too_many_ids":"查询参数中包含太多ID，最多允许 100 个","errors.delete_user_setting_only":"只能删除用户创建的设置","errors.indecipherable":"*无法解析*","errors.cannot_decipher_secret":"密钥不能被解密。这主要是由 2Fauth 的 .env 文件中 APP_KEY 设置错误或存储在数据库中的数据已损坏引发的。","errors.https_required":"需要 HTTPS","errors.browser_does_not_support_webauthn":"您的设备不支持Webauthn。请使用更现代的浏览器重试。","errors.aborted_by_user":"被用户中止。","errors.security_device_already_registered":"设备已被注册过","errors.not_allowed_operation":"不允许此操作","errors.no_authenticator_support_specified_algorithms":"没有身份验证器支持指定的算法","errors.authenticator_missing_discoverable_credential_support":"身份验证器缺少可发现凭据的支持","errors.authenticator_missing_user_verification_support":"身份验证器缺少用户验证支持","errors.unknown_error":"未知错误","errors.security_error_check_rpid":"安全错误<br/>请检查您的 WEBAUTHN_ID env var","errors.2fauth_has_not_a_valid_domain":"2FAuth的域不是一个有效的域","errors.user_id_not_between_1_64":"用户ID不在 1 到 64 个字符内","errors.no_entry_was_of_type_public_key":'没有类型为"公钥"的条目',"errors.unsupported_with_reverseproxy":"使用代理进行认证时不可用","errors.user_deletion_failed":"帐户删除失败，没有数据被删除","errors.auth_proxy_failed":"代理认证失败","errors.auth_proxy_failed_legend":"2Fauth 被配置为在身份验证代理后运行，但您的代理没有返回预期的请求头。请检查您的配置并重试。","errors.invalid_x_migration":"无效或不可读的 :appname 数据","errors.invalid_2fa_data":"无效的2FA数据","errors.unsupported_migration":"数据与任何支持的格式不匹配","errors.unsupported_otp_type":"不支持的 OTP 类型","errors.encrypted_migration":"无法读取，数据似乎已加密","errors.no_logo_found_for_x":"{service} 没有可用的 Logo","errors.file_upload_failed":"文件上传失败","errors.unauthorized":"无权限","errors.unauthorized_legend":"您无权查看此资源或执行此操作","errors.cannot_delete_the_only_admin":"无法删除唯一的管理员账户","errors.cannot_demote_the_only_admin":"Cannot demote the only admin account","errors.error_during_data_fetching":"💀 在获取数据过程中出了问题","errors.check_failed_try_later":"检查失败，请稍后重试","errors.sso_disabled":"SSO 已禁用","errors.sso_bad_provider_setup":"此 SSO 提供商没有在您的 .env 文件中完全设置","errors.sso_failed":"通过 SSO 验证被拒绝","errors.sso_no_register":"注册已禁用","errors.sso_email_already_used":"已存在具有相同电子邮件地址的用户帐户，但它与您的外部帐户ID不匹配。 如果您已经在 2FAuth 上使用此邮箱注册，请不要使用 SSO。","errors.account_managed_by_external_provider":"由外部提供商管理的帐户","errors.data_cannot_be_refreshed_from_server":"无法从服务器刷新数据","errors.no_pwd_reset_for_this_user_type":"此用户无法重置密码","errors.cannot_detect_qrcode_in_image":"Cannot detect a QR code in the image, try to crop the image","errors.cannot_decode_detected_qrcode":"Cannot decode detected QR code, try to crop or sharpen the image","errors.qrcode_has_invalid_checksum":"QR code has invalid checksum","errors.no_readable_qrcode":"No readable QR code","groups.groups":"组","groups.create_group":"创建新组","groups.show_group_selector":"显示分组选择器","groups.hide_group_selector":"隐藏分组选择器","groups.select_accounts_to_show":"选择要显示的帐户分组","groups.x_accounts":":count个帐户","groups.manage_groups":"管理组","groups.active_group":"活跃组","groups.manage_groups_legend":"您可以创建组以按照您想要的方式组织您的账户。 所有账户在名为“全部”的伪组中仍然可见，无论它们属于哪个组。","groups.deleting_group_does_not_delete_accounts":"删除组不会删除帐户","groups.move_selected_to":"移动选择到","groups.move_selected_to_group":"将所选移入组中","groups.no_group":"没有分组","groups.change_group":"更改组","groups.group_successfully_created":"分组成功创建","groups.group_name_saved":"分组名称已保存","groups.group_successfully_deleted":"分组成功删除","groups.forms.new_group":"新建组","groups.forms.new_name":"新名称","groups.forms.rename_group":"重命名组","groups.confirm.delete":"您确定要删除此组吗？","languages.browser_preference":"使用游览器偏好","languages.en":"English (英语)","languages.fr":"Français (法语)","languages.de":"Deutsch (德语)","languages.es":"Español (西班牙语)","languages.zh":"简体中文 (简体中文)","languages.ru":"Русский (俄语)","languages.bg":"Български (保加利亚语)","languages.ja":"日本語 (日本人)","languages.hi":"हिंदी (印地语)","notifications.hello":"您好","notifications.hello_user":"Hello :username,","notifications.regards":"Regards","notifications.test_email_settings.subject":"2FAuth 测试电子邮件","notifications.test_email_settings.reason":"您收到这封邮件是因为您请求了一封测试电子邮件来验证您的2FAuth 实例的电子邮件设置。","notifications.test_email_settings.success":"好消息是，它正常工作:)","notifications.new_device.subject":"Connection to 2FAuth from a new device","notifications.new_device.resume":"A new device has just connected to your 2FAuth account.","notifications.new_device.connection_details":"Here are the details of this connection","notifications.new_device.recommandations":"If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.","notifications.failed_login.subject":"Failed login to 2FAuth","notifications.failed_login.resume":"There has been a failed login attempt to your 2FAuth account.","notifications.failed_login.connection_details":"Here are the details of this connection attempt","notifications.failed_login.recommandations":"If this was you, you can ignore this alert. If further attempts fail, you should contact the 2FAuth administrator to review security settings and take action against this attacker.","pagination.previous":"&laquo; 上一页","pagination.next":"下一页 &raquo;","passwords.reset":"密码重置成功！","passwords.sent":"密码重置邮件已发送！","passwords.throttled":"请稍候再试。","passwords.token":"密码重置令牌无效。","passwords.user":"找不到该邮箱对应的用户。","passwords.password":"密码必须包含至少8个字符，且两次输入的内容必须相同。","settings.settings":"设置","settings.preferences":"偏好","settings.account":"账户","settings.oauth":"OAuth","settings.webauthn":"WebAuthn","settings.tokens":"令牌","settings.options":"选项","settings.user_preferences":"用户偏好","settings.admin_settings":"管理员设置","settings.confirm.revoke":"你确定要吊销此令牌？","settings.you_are_administrator":"您是管理员","settings.account_linked_to_sso_x_provider":"您通过SSO使用您的 :provider 帐户登录。您的信息不能在这里更改，只能在 :provider 。","settings.general":"常规","settings.security":"安全","settings.notifications":"Notifications","settings.profile":"配置文件","settings.change_password":"更改密码","settings.personal_access_tokens":"个人访问令牌","settings.token_legend":"个人访问令牌允许任何应用访问 2Fauth API。您应该在第三方应用授权请求头中提供访问令牌作为一个 Bearer 令牌。","settings.generate_new_token":"生成新令牌","settings.revoke":"吊销","settings.token_revoked":"已成功吊销令牌","settings.revoking_a_token_is_permanent":"吊销令牌是永久的","settings.make_sure_copy_token":"请确保您已复制个人访问令牌。令牌将不再显示。","settings.data_input":"数据输入","settings.forms.edit_settings":"编辑设置","settings.forms.setting_saved":"设置已保存","settings.forms.new_token":"新建令牌","settings.forms.some_translation_are_missing":"使用浏览器偏好时缺少一些翻译？","settings.forms.help_translate_2fauth":"帮助翻译 2FAuth","settings.forms.language.label":"语言","settings.forms.language.help":"用来翻译 2FAuth 用户界面的语言。列出的语言已完成翻译，请设置你选择的语言来覆盖你的浏览器偏好。","settings.forms.timezone.label":"Time zone","settings.forms.timezone.help":"The time zone applied to all dates and times displayed in the application","settings.forms.show_otp_as_dot.label":"用 *** 来显示生成的一次性密码","settings.forms.show_otp_as_dot.help":"将生成的密码替换为 *** 以确保保密。不影响复制和粘贴功能。","settings.forms.reveal_dotted_otp.label":'显示被 *** 隐藏的 <abbr title="One-Time Password">OTP</abbr>',"settings.forms.reveal_dotted_otp.help":"允许临时显示被 *** 隐藏的密码","settings.forms.close_otp_on_copy.label":'复制后关闭 <abbr title="One-Time Password">OTP</abbr>',"settings.forms.close_otp_on_copy.help":"点击生成的密码进行复制，并自动将其从屏幕上隐藏","settings.forms.clear_search_on_copy.label":"复制后清空搜索框","settings.forms.clear_search_on_copy.help":"代码复制到剪贴板后立即清空搜索框","settings.forms.copy_otp_on_display.label":'在显示时复制 <abbr title="One-Time Password">OTP</abbr>',"settings.forms.copy_otp_on_display.help":'在屏幕显示后自动复制生成的密码。 由于浏览器限制，只有第一个 <abbr title="Time-based One-Time Password">TOTP</abbr> 密码将被复制，而不是更新后的',"settings.forms.use_basic_qrcode_reader.label":"使用基本二维码读取器","settings.forms.use_basic_qrcode_reader.help":"如果你在扫描二维码时遇到问题，这个选项可以切换到更基本但更可靠的二维码阅读器","settings.forms.display_mode.label":"显示模式","settings.forms.display_mode.help":"选择将账户以列表或网格的方式进行展示","settings.forms.password_format.label":"密码格式","settings.forms.password_format.help":"分组显示密码，提高可读性并且便于记忆","settings.forms.pair":"两位数分组","settings.forms.pair_legend":"以两位数分组拆分数字","settings.forms.trio_legend":"以三位数分组拆分数字","settings.forms.half_legend":"将数字拆分为两个相等数位的分组","settings.forms.trio":"三位数分组","settings.forms.half":"两个相等数位的分组","settings.forms.grid":"网格","settings.forms.list":"列表","settings.forms.theme.label":"主题","settings.forms.theme.help":"强制一个特定主题或应用系统/浏览器首选项中定义的主题","settings.forms.light":"亮色主题","settings.forms.dark":"暗色主题","settings.forms.automatic":"自动检测","settings.forms.show_accounts_icons.label":"显示图标","settings.forms.show_accounts_icons.help":"在主视图中显示应用图标","settings.forms.get_official_icons.label":"获取官方图标","settings.forms.get_official_icons.help":"(尝试) 在添加账户时获取两步验证发行者的官方图标","settings.forms.auto_lock.label":"自动锁定","settings.forms.auto_lock.help":"在没有活动的情况下自动登出用户。当使用认证代理或没有指定自定义注销 URL 时无效。","settings.forms.default_group.label":"默认分组","settings.forms.default_group.help":"新创建的账户所关联的分组","settings.forms.view_default_group_on_copy.label":"在复制后查看默认组","settings.forms.view_default_group_on_copy.help":"复制OTP后总是返回到默认组","settings.forms.useDirectCapture.label":"直接输入","settings.forms.useDirectCapture.help":"选择您是否想要在可用的输入模式中选择输入模式，或者直接使用默认输入模式","settings.forms.defaultCaptureMode.label":"默认输入模式","settings.forms.defaultCaptureMode.help":"直接输入选项开启时使用的默认输入模式","settings.forms.remember_active_group.label":"记住组筛选器","settings.forms.remember_active_group.help":"保存最后应用的组过滤器并在下次访问时还原它","settings.forms.otp_generation.label":"显示密码","settings.forms.otp_generation.help":'设置 <abbr title="One-Time Passwords">OTPs</abbr> 的显示方式和时间。<br/>',"settings.forms.notify_on_new_auth_device.label":"On new device","settings.forms.notify_on_new_auth_device.help":"Get an email when a new device connects to your 2FAuth account for the first time","settings.forms.notify_on_failed_login.label":"On failed login","settings.forms.notify_on_failed_login.help":"Get an email each time an attempt to connect to your 2FAuth account fails","settings.forms.otp_generation_on_request":"点击/单击账户后","settings.forms.otp_generation_on_request_legend":"在专用视图中显示密码","settings.forms.otp_generation_on_request_title":"单击帐户在专用视图中获取密码","settings.forms.otp_generation_on_home":"始终","settings.forms.otp_generation_on_home_legend":"主视图中显示所有密码","settings.forms.otp_generation_on_home_title":"在主视图中显示所有密码，无需任何操作","settings.forms.never":"从不","settings.forms.on_otp_copy":"在复制安全代码后","settings.forms.1_minutes":"1分钟后","settings.forms.5_minutes":"5分钟后","settings.forms.10_minutes":"10 分钟后","settings.forms.15_minutes":"15分钟后","settings.forms.30_minutes":"30 分钟后","settings.forms.1_hour":"1小时后","settings.forms.1_day":"1天后","settings.forms.livescan":"扫描二维码","settings.forms.upload":"上传二维码","settings.forms.advanced_form":"高级表单","titles.404":"找不到项目","titles.start":"新账户","titles.capture":"扫描二维码","titles.accounts":"账户","titles.createAccount":"创建账户","titles.importAccounts":"导入账户","titles.editAccount":"编辑账户","titles.showQRcode":"二维码形式的账户","titles.groups":"组","titles.createGroup":"创建组","titles.editGroup":"编辑组","titles.settings.options":"选项","titles.settings.account":"用户帐户","titles.settings.oauth.tokens":"OAuth 令牌","titles.settings.oauth.generatePAT":"新建个人令牌","titles.settings.webauthn.editCredential":"编辑设备","titles.settings.webauthn.devices":"WebAuthn 设备","titles.login":"登录","titles.register":"注册","titles.autolock":"自动锁定","titles.password.request":"重置密码","titles.password.reset":"新密码","titles.webauthn.lost":"恢复账号","titles.webauthn.recover":"注册新设备","titles.flooded":"请求数过多","titles.genericError":"错误","titles.about":"关于","titles.admin.appSetup":"应用设置","titles.admin.users":"用户管理","titles.admin.createUser":"创建用户","titles.admin.manageUser":"管理用户","titles.admin.logs.access":"Access log","twofaccounts.service":"服务","twofaccounts.account":"账户","twofaccounts.icon":"图标","twofaccounts.icon_for_account_x_at_service_y":"{account} 在 {service} 的帐户图标","twofaccounts.icon_to_illustrate_the_account":"说明账户的图标","twofaccounts.remove_icon":"移除图标","twofaccounts.no_account_here":"这里没有两步验证！","twofaccounts.add_first_account":"选择一个方法并添加您的第一个帐户","twofaccounts.use_full_form":"或者使用完整的表单","twofaccounts.add_one":"添加一个","twofaccounts.show_qrcode":"显示 QR 码","twofaccounts.no_service":"- 无服务 -","twofaccounts.account_created":"帐户成功创建","twofaccounts.account_updated":"账户成功更新","twofaccounts.accounts_deleted":"帐户成功删除","twofaccounts.accounts_moved":"帐户成功移动","twofaccounts.export_selected_to_json":"将所选账号以json导出","twofaccounts.reveal":"显示","twofaccounts.forms.service.placeholder":"谷歌, 推特, 苹果","twofaccounts.forms.account.placeholder":"李华","twofaccounts.forms.new_account":"新建账户","twofaccounts.forms.edit_account":"编辑账户","twofaccounts.forms.otp_uri":"OTP Uri","twofaccounts.forms.scan_qrcode":"扫描QR码","twofaccounts.forms.upload_qrcode":"上传一个QR码","twofaccounts.forms.use_advanced_form":"使用高级表单","twofaccounts.forms.prefill_using_qrcode":"使用QR码进行预填充","twofaccounts.forms.use_qrcode.val":"使用一个QR码","twofaccounts.forms.use_qrcode.title":"使用QR码来自动填充表单","twofaccounts.forms.unlock.val":"解锁","twofaccounts.forms.unlock.title":"解锁它(风险自负)","twofaccounts.forms.lock.val":"锁定","twofaccounts.forms.lock.title":"将其锁定","twofaccounts.forms.choose_image":"上传","twofaccounts.forms.i_m_lucky":"手气不错","twofaccounts.forms.i_m_lucky_legend":'"手气不错"按钮会尝试获取指定服务的官方图标。输入实际的英文服务名（不带后缀）并避免输入错误。(测试中的功能)',"twofaccounts.forms.test":"测试","twofaccounts.forms.secret.label":"密钥","twofaccounts.forms.secret.help":"用于生成安全码的密钥","twofaccounts.forms.plain_text":"纯文本","twofaccounts.forms.otp_type.label":'选择要创建的 <abbr title="One-Time Password">OTP</abbr> 类型',"twofaccounts.forms.otp_type.help":"基于 时间的OTP(TOTP) 或 基于HMAC的OTP(HMAC-based OTP) 或 Steam OTP","twofaccounts.forms.digits.label":"码长","twofaccounts.forms.digits.help":"生成的安全码位数","twofaccounts.forms.algorithm.label":"算法","twofaccounts.forms.algorithm.help":"用于保护您的安全代码的算法","twofaccounts.forms.period.label":"周期","twofaccounts.forms.period.placeholder":"默认为30","twofaccounts.forms.period.help":"生成的二维码的以秒为单位的有效期限","twofaccounts.forms.counter.label":"计数器","twofaccounts.forms.counter.placeholder":"默认为0","twofaccounts.forms.counter.help":"初始计数器值","twofaccounts.forms.counter.help_lock":"编辑计数器是危险的，因为您可能使帐户与服务的验证服务器失去同步。点击锁的图标可启用更改，但只应在您知道您在做什么时使用","twofaccounts.forms.image.label":"图像","twofaccounts.forms.image.placeholder":"http://...","twofaccounts.forms.image.help":"作为帐户图标的 URL","twofaccounts.forms.options_help":"如果您不知道如何填写，您可以将下列选项留空。将会应用最常见的设置。","twofaccounts.forms.alternative_methods":"备选方法","twofaccounts.forms.spaces_are_ignored":"不需要的空格将被自动删除","twofaccounts.stream.live_scan_cant_start":"扫描无法开始 :(","twofaccounts.stream.need_grant_permission.reason":"2FAuth 没有权限访问您的相机","twofaccounts.stream.need_grant_permission.solution":"您需要授予权限才能使用您的设备相机。 如果您已经拒绝，且您的浏览器不会再次提示您，请参考浏览器文档以了解如何授予权限。","twofaccounts.stream.need_grant_permission.click_camera_icon":"它通常是通过点击浏览器地址栏中或旁边的虚线相机图标来完成的。","twofaccounts.stream.not_readable.reason":"载入扫描仪失败","twofaccounts.stream.not_readable.solution":"摄像头是否已在使用？请确保没有其他应用使用您的摄像头并重试","twofaccounts.stream.no_cam_on_device.reason":"此设备上没有摄像头","twofaccounts.stream.no_cam_on_device.solution":"也许你忘了插上你的摄像头","twofaccounts.stream.secured_context_required.reason":"需要安全上下文","twofaccounts.stream.secured_context_required.solution":"实时扫描需要HTTPS。如果您从计算机运行2FAuth，请不要使用localhost以外的虚拟主机","twofaccounts.stream.https_required":"摄像机需要 HTTPS","twofaccounts.stream.camera_not_suitable.reason":"已安装的摄像头不合适。","twofaccounts.stream.camera_not_suitable.solution":"请使用其他摄像头或更换设备","twofaccounts.stream.stream_api_not_supported.reason":"此浏览器不支持 Stream API","twofaccounts.stream.stream_api_not_supported.solution":"您应该使用一个现代浏览器","twofaccounts.confirm.delete":"你确定要删除这个账户吗？","twofaccounts.confirm.cancel":"帐户将丢失。您确定吗？","twofaccounts.confirm.discard":"您确定要放弃此账户吗？","twofaccounts.confirm.discard_all":"您确定要放弃所有账户吗？","twofaccounts.confirm.discard_duplicates":"您确定要放弃所有重复账户吗？","twofaccounts.import.import":"导入","twofaccounts.import.to_import":"导入","twofaccounts.import.import_legend":"2FAuth 可以从各种2FA 应用程序导入数据。<br />使用这些应用的导出功能来获取迁移资源(QR码或文件)，并在下方加载它。","twofaccounts.import.import_legend_afterpart":"使用这些应用的导出功能来获取迁移资源，例如二维码或JSON文件，然后加载它。","twofaccounts.import.upload":"上传","twofaccounts.import.scan":"扫描","twofaccounts.import.supported_formats_for_qrcode_upload":"接受：jpg、jpeg、png、bmp、gif、svg或webp","twofaccounts.import.supported_formats_for_file_upload":"接受：纯文本，json，2fas","twofaccounts.import.expected_format_for_direct_input":"应为：一个 otpauth URI 的列表，一行一个","twofaccounts.import.supported_migration_formats":"支持的迁移格式","twofaccounts.import.qr_code":"二维码","twofaccounts.import.text_file":"文本文件","twofaccounts.import.direct_input":"直接输入","twofaccounts.import.plain_text":"纯文本","twofaccounts.import.parsing_data":"正在解析数据...","twofaccounts.import.issuer":"发行商","twofaccounts.import.imported":"已导入","twofaccounts.import.failure":"失败","twofaccounts.import.x_valid_accounts_found":"找到 {count} 个有效账户","twofaccounts.import.submitted_data_parsed_now_accounts_are_awaiting_import":"在迁移资源中找到了以下2FA账户。到目前为止，它们都没有被添加到2FAuth中。","twofaccounts.import.use_buttons_to_save_or_discard":"使用可用的按钮将它们永久保存到您的两步验证集合或丢弃它们。","twofaccounts.import.import_all":"全部导入","twofaccounts.import.import_this_account":"导入此账户","twofaccounts.import.discard_all":"全部丢弃","twofaccounts.import.discard_duplicates":"丢弃重复项","twofaccounts.import.discard_this_account":"丢弃此帐户","twofaccounts.import.generate_a_test_password":"生成一个测试密码","twofaccounts.import.possible_duplicate":"完全相同的帐户已经存在","twofaccounts.import.invalid_account":"- 无效账户 -","twofaccounts.import.invalid_service":"- 无效服务 -","twofaccounts.import.do_not_set_password_or_encryption":"当您想要导入到2FAuth时不要启用密码保护或加密。","validation.accepted":"您必须接受 :attribute。","validation.accepted_if":":attribute 只有在 :other 为 :value 时才有效","validation.active_url":":attribute 不是一个有效的网址。","validation.after":":attribute 必须要晚于 :date。","validation.after_or_equal":":attribute 必须要等于 :date 或更晚。","validation.alpha":":attribute 只能包含字母。","validation.alpha_dash":":attribute 只能包含字母、 数字、 破折号和下划线","validation.alpha_num":":attribute 只能包含字母和数字","validation.array":":attribute 必须是一个数组。","validation.before":":attribute 必须要早于 :date。","validation.before_or_equal":":attribute 必须要等于 :date 或更早。","validation.between.array":":attribute 必须只有 :min - :max 个单元。","validation.between.file":":attribute 必须介于 :min - :max KB 之间。","validation.between.numeric":":attribute 必须介于 :min - :max 之间。","validation.between.string":":attribute 必须介于 :min - :max 个字符之间。","validation.boolean":":attribute 必须为布尔值。","validation.confirmed":":attribute 两次输入不一致。","validation.current_password":"密码错误","validation.date":":attribute 不是一个有效的日期。","validation.date_equals":":attribute 必须要等于 :date。","validation.date_format":":attribute 的格式必须为 :format。","validation.declined":"您必须同意 :attribute.","validation.declined_if":":attribute 在 :other 是 :value 时无效.","validation.different":":attribute 和 :other 必须不同。","validation.digits":":attribute 必须是 :digits 位数字。","validation.digits_between":":attribute 必须是介于 :min 和 :max 位的数字。","validation.dimensions":":attribute 图片尺寸不正确。","validation.distinct":":attribute 已经存在。","validation.doesnt_end_with":":attribute 不能以下列的值结尾: :values。","validation.doesnt_start_with":":attribute 不能以下列的值开头: :values。","validation.email":":attribute 不是一个合法的邮箱。","validation.ends_with":":attribute 必须以 :values 为结尾。","validation.enum":"已选的属性 :attribute 无效。","validation.exists":":attribute 不存在。","validation.file":":attribute 必须是文件。","validation.filled":":attribute 不能为空。","validation.gt.array":":attribute 必须多于 :value 个元素。","validation.gt.file":":attribute 必须大于 :value KB。","validation.gt.numeric":":attribute 必须大于 :value。","validation.gt.string":":attribute 必须多于 :value 个字符。","validation.gte.array":":attribute 必须多于或等于 :value 个元素。","validation.gte.file":":attribute 必须大于或等于 :value kB。","validation.gte.numeric":":attribute 必须大于或等于 :value。","validation.gte.string":":attribute 必须大于或等于 :value 个字符。","validation.image":":attribute 必须是图片。","validation.in":"已选的属性 :attribute 无效。","validation.in_array":":attribute 必须在 :other 中。","validation.integer":":attribute 必须是整数。","validation.ip":":attribute 必须是有效的 IP 地址。","validation.ipv4":":attribute 必须是有效的 IPv4 地址。","validation.ipv6":":attribute 必须是有效的 IPv6 地址。","validation.json":":attribute 必须是正确的 JSON 格式。","validation.lt.array":":attribute 必须少于 :value 个元素。","validation.lt.file":":attribute 必须小于 :value KB。","validation.lt.numeric":":attribute 必须小于 :value。","validation.lt.string":":attribute 必须少于 :value 个字符。","validation.lte.array":":attribute 必须少于或等于 :value 个元素。","validation.lte.file":":attribute 必须小于或等于 :value kB。","validation.lte.numeric":":attribute 必须小于或等于 :value。","validation.lte.string":":attribute 必须小于或等于 :value 个字符。","validation.mac_address":":attribute 必须是一个有效的 MAC 地址。","validation.max.array":":attribute 必须少于或等于 :value 个元素。","validation.max.file":":attribute 不能大于 :value kB。","validation.max.numeric":":attribute 不能大于 :max。","validation.max.string":":attribute 不能大于 :max 个字符。","validation.max_digits":":attribute 不能超过 :max 位数。","validation.mimes":":attribute 必须是一个 :values 类型的文件。","validation.mimetypes":":attribute 必须是一个 :values 类型的文件。","validation.min.array":":attribute 至少有 :min 个单元。","validation.min.file":":attribute 大小不能小于 :min KB。","validation.min.numeric":":attribute 必须大于等于 :min。","validation.min.string":":attribute 至少为 :min 个字符。","validation.min_digits":":attribute 至少要有 :min 位数。","validation.multiple_of":":attribute 必须是 :value 的倍数","validation.not_in":"已选的属性 :attribute 非法。","validation.not_regex":":attribute 的格式错误。","validation.numeric":":attribute 必须是一个数字。","validation.password.letters":":attribute 至少要包含一个字母。","validation.password.mixed":":attribute 至少要包含一个大写字母和一个小写字母。","validation.password.numbers":":attribute 至少要包含一个数字。","validation.password.symbols":":attribute 至少要包含一个符号。","validation.password.uncompromised":"在数据泄漏中检测到已输入的 :attribute。请选择一个不同的 :attribute 。","validation.present":":attribute 必须存在。","validation.prohibited":":attribute 字段是禁止的.","validation.prohibited_if":"当 :other 为 :value 时, :attribute 字段被禁止","validation.prohibited_unless":"除非 :other 为 :values，否则 :attribute 字段是禁止的","validation.prohibits":':attribute 字段禁止出现 ":other"',"validation.regex":":attribute 格式不正确。","validation.required":":attribute 不能为空。","validation.required_array_keys":":attribute 字段必须包含: :values","validation.required_if":"当 :other 为 :value 时 :attribute 不能为空。","validation.required_if_accepted":"当 :other 存在时，:attribute 不能为空。","validation.required_unless":"当 :other 不为 :values 时 :attribute 不能为空。","validation.required_with":"当 :values 存在时 :attribute 不能为空。","validation.required_with_all":"当 :values 存在时 :attribute 不能为空。","validation.required_without":"当 :values 不存在时 :attribute 不能为空。","validation.required_without_all":"当 :values 都不存在时 :attribute 不能为空。","validation.same":":attribute 和 :other 必须相同。","validation.size.array":":attribute 必须为 :size 个单元。","validation.size.file":":attribute 大小必须为 :size KB。","validation.size.numeric":":attribute 大小必须为 :size。","validation.size.string":":attribute 必须是 :size 个字符。","validation.starts_with":":attribute 必须以 :values 为开头。","validation.string":":attribute 必须是一个字符串。","validation.timezone":":attribute 必须是一个有效的时区。","validation.unique":":attribute 已经存在。","validation.uploaded":":attribute 上传失败。","validation.url":":attribute 必须是有效的URL。","validation.uuid":":attribute 必须是有效的 UUID。","validation.single":"当使用 :attribute 时，它必须是此请求主体中的唯一参数","validation.onlyCustomOtpWithUri":'"uri"参数仅应单独提供，或与"custom_otp"参数结合提供',"validation.custom.icon.image":"支持的格式是 jpeg、png、bmp、gif、svg或web。","validation.custom.qrcode.image":"支持的格式是 jpeg、png、bmp、gif、svg或web。","validation.custom.uri.regex":":attribute 不是有效的 otpauth uri","validation.custom.otp_type.in":":attribute 不受支持","validation.custom.email.exists":"未找到使用此电子邮件的账户。","validation.custom.email.ComplyWithEmailRestrictionPolicy":"此电子邮件地址不符合注册策略","validation.custom.email.IsValidEmailList":"所有电子邮件必须是有效的并用管道符隔开","validation.custom.secret.isBase32Encoded":":attribute 必须是 base32 编码的字符串","validation.custom.account.regex":":attribute 不能包含冒号。","validation.custom.service.regex":":attribute 不能包含冒号。","validation.custom.label.required":"uri 必须有一个标签。","validation.custom.ids.regex":"ID必须以逗号分隔，无需尾随逗号。"};export{t as default};
