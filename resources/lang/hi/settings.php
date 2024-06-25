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

    'settings' => 'सेटिंग्स',
    'preferences' => 'प्राथमिकताएं',
    'account' => 'अकाउंट',
    'oauth' => 'OAuth',
    'webauthn' => 'WebAuthn',
    'tokens' => 'टोकन',
    'options' => 'विकल्प',
    'user_preferences' => 'उपयोगकर्ता की प्राथमिकताएं',
    'admin_settings' => 'ऐड्मिन की सेटिंग',
    'confirm' => [

    ],
    'you_are_administrator' => 'आप एक व्यवस्थापक (ऐड्मिन) हैं',
    'account_linked_to_sso_x_provider' => 'आपने अपने :provider खाते का उपयोग करके SSO के माध्यम से साइन-इन किया है। आपकी जानकारी यहां :provider के अलावा नहीं बदली जा सकती।',
    'general' => 'सामान्य',
    'security' => 'सुरक्षा',
    'notifications' => 'Notifications',
    'profile' => 'प्रोफ़ाइल',
    'change_password' => 'पासवर्ड बदलें',
    'personal_access_tokens' => 'प्रवेश के व्यक्तिगत टोकन',
    'token_legend' => 'व्यक्तिगत एक्सेस टोकन किसी भी ऐप को 2Fauth API को प्रमाणित करने की अनुमति देते हैं। आपको उपभोक्ता ऐप के अनुरोधों के प्राधिकरण हेडर में एक्सेस टोकन को बियरर टोकन के रूप में निर्दिष्ट करना चाहिए।',
    'generate_new_token' => 'एक नया टोकन बनाएं',
    'revoke' => 'वापस लें',
    'token_revoked' => 'टोकन सफलतापूर्वक निरस्त हो गया है',
    'revoking_a_token_is_permanent' => 'टोकन का निरस्त्रीकरण स्थायी होता है',
    'confirm' => [
        'revoke' => 'क्या आप वास्तव में इस टोकन को निरस्त करना चाहते हैं?',
    ],
    'make_sure_copy_token' => 'अपने नए व्यक्तिगत एक्सेस टोकन को अभी कॉपी करना सुनिश्चित करें। आप इसे दोबारा नहीं देख पाएंगे!',
    'data_input' => 'डेटा इनपुट',
    'forms' => [
        'edit_settings' => 'सेटिंग्स बदलें',
        'setting_saved' => 'सेटिंग्स सेव हो गयी',
        'new_token' => 'नया टोकन',
        'some_translation_are_missing' => 'ब्राउज़र की पसंदीदा भाषा का उपयोग करते हुए क्या कुछ अनुवाद गायब हैं?',
        'help_translate_2fauth' => '2FAuth का अनुवाद करने में सहायता करें',
        'language' => [
            'label' => 'भाषा ',
            'help' => '2FAuth उपयोगकर्ता इंटरफ़ेस का अनुवाद करने के लिए उपयोग की जाने वाली भाषा। नामित भाषाएँ पूर्ण हैं, अपनी ब्राउज़र प्राथमिकता को ओवरराइड करने के लिए अपनी पसंद में से एक भाषा को चुनें।'
        ],
        'timezone' => [
            'label' => 'टाइम ज़ोन',
            'help' => 'एप में दिखाए गए सभी समय और तारीखों पर यह टाइम ज़ोन लागू होगा'
        ],
        'show_otp_as_dot' => [
            'label' => 'जनरेट किए गए <abbr title="वन-टाइम पासवर्ड">OTP</abbr> को डॉट के रूप में दिखाएं',
            'help' => 'गोपनीयता सुनिश्चित करने के लिए जनरेट किए गए पासवर्ड वर्णों को *** से बदलें। कॉपी/पेस्ट सुविधा को प्रभावित न करें'
        ],
        'reveal_dotted_otp' => [
            'label' => 'अस्पष्ट <abbr title="वन-टाइम पासवर्ड">OTP</abbr> को प्रकट करें',
            'help' => 'डॉट-ऑब्स्क्योर्ड पासवर्ड को अस्थायी रूप से प्रकट करने की क्षमता दें'
        ],
        'close_otp_on_copy' => [
            'label' => 'कॉपी करने के बाद <abbr title="वन-टाइम पासवर्ड">OTP</abbr> बंद करें',
            'help' => 'Click on a generated password to copy it automatically hides it from the screen'
        ],
        'auto_close_timeout' => [
            'label' => 'Auto close <abbr title="One-Time Password">OTP</abbr>',
            'help' => 'Automatically hide on-screen password after a timeout. This avoids unnecessary requests for fresh passwords if you forget to close the password view.'
        ],
        'clear_search_on_copy' => [
            'label' => 'कॉपी होने पर खोज मिटा दें',
            'help' => 'क्लिपबोर्ड पर कोड कॉपी होने के ठीक बाद सर्च बॉक्स को खाली कर दें'
        ],
        'sort_case_sensitive' => [
            'label' => 'Sort case sensitive',
            'help' => 'When invoked, force the Sort function to sort accounts on a case-sensitive basis'
        ],
        'copy_otp_on_display' => [
            'label' => 'डिस्प्ले पर <abbr title="वन-टाइम पासवर्ड">OTP</abbr> कॉपी करें',
            'help' => 'जनरेट किया गया पासवर्ड स्क्रीन पर दिखाई देने के तुरंत बाद स्वचालित रूप से कॉपी हो जाता है। ब्राउज़र की सीमाओं के कारण, बदलने वाले पासवर्ड नहीं, केवल पहला <abbr title="समय-आधारित वन-टाइम पासवर्ड">TOTP</abbr> पासवर्ड कॉपी किया जाएगा'
        ],
        'use_basic_qrcode_reader' => [
            'label' => 'बेसिक QR कोड रीडर का उपयोग करें',
            'help' => 'यदि आप QR कोड कैप्चर करते समय समस्याओं का अनुभव करते हैं तो यह विकल्प अधिक बुनियादी लेकिन अधिक विश्वसनीय QR कोड रीडर पर स्विच करने में सक्षम बनाता है'
        ],
        'display_mode' => [
            'label' => 'डिस्प्ले मोड',
            'help' => 'चुनें कि आप खातों को सूची के रूप में प्रदर्शित करना चाहते हैं या ग्रिड के रूप में'
        ],
        'password_format' => [
            'label' => 'पासवर्ड फ़ॉर्मेटिंग',
            'help' => 'पठनीयता और याद रखने में आसानी के लिए अंकों को समूहीकृत करके पासवर्ड प्रदर्शित करने का तरीका बदलें'
        ],
        'pair' => 'जोड़े से',
        'pair_legend' => 'अंकों को दो बटा दो समूहित करें',
        'trio_legend' => 'अंकों को तीन बटा तीन समूहित करें',
        'half_legend' => 'अंकों को दो बराबर समूहों में विभाजित करें',
        'trio' => 'तिकड़ी से',
        'half' => 'आधे में',
        'grid' => 'ग्रिड',
        'list' => 'सूची',
        'theme' => [
            'label' => 'थीम',
            'help' => 'किसी विशिष्ट थीम को बाध्य करें या अपने सिस्टम/ब्राउज़र प्राथमिकताओं में परिभाषित थीम को लागू करें'
        ],
        'light' => 'हल्का रंग',
        'dark' => 'गहरा रंग',
        'automatic' => 'ऑटो',
        'show_accounts_icons' => [
            'label' => 'आइकन दिखाएं',
            'help' => 'मुख्य दृश्य में आइकान के अकाउंट दिखाएँ'
        ],
        'get_official_icons' => [
            'label' => 'आधिकारिक आइकान प्राप्त करें',
            'help' => 'खाता जोड़ते समय 2FA जारीकर्ता का आधिकारिक आइकन प्राप्त करें (कोशिश करें)'
        ],
        'auto_lock' => [
            'label' => 'ऑटो-लॉक',
            'help' => 'निष्क्रियता की स्थिति में उपयोगकर्ता को स्वचालित रूप से लॉग आउट करें। जब प्रमाणीकरण को प्रॉक्सी द्वारा नियंत्रित किया जाता है और कोई कस्टम लॉगआउट URL निर्दिष्ट नहीं किया जाता है तो इसका कोई प्रभाव नहीं पड़ता है।'
        ],
        'default_group' => [
            'label' => 'डिफ़ॉल्ट ग्रुप',
            'help' => 'वह समूह जिससे नव निर्मित अकाउंट जुड़े हैं',
        ],
        'view_default_group_on_copy' => [
            'label' => 'कॉपी होने पर डिफ़ॉल्ट ग्रुप देखें',
            'help' => 'जब भी OTP कॉपी हो जाए तो हमेशा डिफ़ॉल्ट ग्रुप पर वापस आ जाएं',
        ],
        'auto_save_qrcoded_account' => [
            'label' => 'Auto-save accounts',
            'help' => 'New accounts are automatically registered after scanning or uploading a QR code, no need to click a Save button',
        ],
        'useDirectCapture' => [
            'label' => 'सीधे दर्ज करें',
            'help' => 'चुनें कि क्या आप उपलब्ध इनपुट मोड में से एक इनपुट मोड चुनने की सुविधा चाहते हैं या आप सीधे डिफ़ॉल्ट इनपुट मोड का उपयोग करना चाहते हैं',
        ],
        'defaultCaptureMode' => [
            'label' => 'डिफ़ॉल्ट इनपुट मोड',
            'help' => 'डायरेक्ट इनपुट विकल्प चालू होने पर डिफ़ॉल्ट इनपुट मोड का उपयोग किया जाता है',
        ],
        'remember_active_group' => [
            'label' => 'ग्रुप फ़िल्टर याद रखें',
            'help' => 'लागू किए गए अंतिम ग्रुप फ़िल्टर को सहेजें और अगली बार इसे पुनर्स्थापित करें',
        ],
        'otp_generation' => [
            'label' => 'पासवर्ड दिखाएँ',
            'help' => 'सेट करें कि <abbr title="वन-टाइम पासवर्ड">OTP</abbr> कैसे और कब प्रदर्शित हों।<br/>',
        ],
        'notify_on_new_auth_device' => [
            'label' => 'On new device',
            'help' => 'Get an email when a new device connects to your 2FAuth account for the first time'
        ],
        'notify_on_failed_login' => [
            'label' => 'On failed login',
            'help' => 'Get an email each time an attempt to connect to your 2FAuth account fails'
        ],
        'otp_generation_on_request' => 'क्लिक / टैप के बाद',
        'otp_generation_on_request_legend' => 'अपने दृश्य में अकेला',
        'otp_generation_on_request_title' => 'पासवर्ड को अलग दृश्य में प्राप्त करने के लिए किसी खाते पर क्लिक करें',
        'otp_generation_on_home' => 'लगातार',
        'otp_generation_on_home_legend' => 'वे सभी, होम पर',
        'otp_generation_on_home_title' => 'बिना कुछ किए सभी पासवर्ड मुख्य दृश्य में दिखाएं',
        'never' => 'कभी नहीं',
        'on_otp_copy' => 'सुरक्षा कोड कॉपी होने पर',
        '1_minutes' => '१ मिनट बाद',
        '2_minutes' => 'After 2 minutes',
        '5_minutes' => '५ मिनट बाद',
        '10_minutes' => '10 मिनट बाद',
        '15_minutes' => '15 मिनट बाद',
        '30_minutes' => '30 मिनट बाद',
        '1_hour' => '1 घंटे बाद',
        '1_day' => '1 दिन के बाद',
        'livescan' => 'QR कोड का लाइव स्कैन',
        'upload' => 'QR कोड का अप-लोड',
        'advanced_form' => 'उन्नत फॉर्म',
    ],

];