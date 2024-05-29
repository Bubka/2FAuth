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
    'failed' => 'यह परिचय हमारे रिकॉर्ड से मेल नहीं खाता',
    'password' => 'दिया गया पासवर्ड ग़लत है।',
    'throttle' => 'बहुत सारे लॉगिन के प्रयास। :seconds सेकंड में फिर से कोशिश करें।',

    // 2FAuth
    'sign_out' => 'साइन आउट करें',
    'sign_in' => 'साइन इन करें',
    'sign_in_using' => 'साइन इन के लिए चाहिए',
    'or_continue_with' => 'आप ऐसे भी आगे जा सकते हैं:',
    'sign_in_using_security_device' => 'सुरक्षा उपकरण का उपयोग करके साइन इन करें',
    'login_and_password' => 'लॉग इन और पासवर्ड',
    'register' => 'रजिस्टर',
    'welcome_to_2fauth' => '2FAuth में आपका स्वागत है',
    'autolock_triggered' => 'ऑटो लॉक चालू हो गया है',
    'autolock_triggered_punchline' => 'ऑटो-लॉक ट्रिगर हो गया है और आप लॉग आउट हो गए हैं',
    'already_authenticated' => 'पहले से ही प्रमाणित है, कृपया पहले लॉग आउट करें',
    'authentication' => 'प्रमाणीकरण',
    'maybe_later' => 'शायद बाद में',
    'user_account_controlled_by_proxy' => 'उपयोगकर्ता का खाता प्रमाणीकरण प्रॉक्सी द्वारा उपलब्ध कराया गया है।<br />खाते को प्रॉक्सी स्तर पर प्रबंधित करें।',
    'auth_handled_by_proxy' => 'प्रमाणीकरण को रिवर्स प्रॉक्सी द्वारा नियंत्रित किया गया है, नीचे दी गई सेटिंग्स अक्षम हैं।<br />प्रॉक्सी स्तर पर प्रमाणीकरण प्रबंधित करें।',
    'confirm' => [
        'logout' => 'क्या आप वास्तव में लॉग आउट करना चाहते हैं?',
        'revoke_device' => 'क्या आप वास्तव में इस डिवाइस को निरस्त करना चाहते हैं?',
        'delete_account' => 'क्या आप वास्तव में अपना अकाउंट डिलीट करना चाहते हैं?',
    ],
    'webauthn' => [
        'security_device' => 'एक सुरक्षा उपकरण',
        'security_devices' => 'सुरक्षा उपकरण',
        'security_devices_legend' => 'प्रमाणीकरण उपकरण जिनका उपयोग आप 2FAuth में साइन इन करने के लिए कर सकते हैं, जैसे सुरक्षा कुंजी (यानी Yubikey) या बायोमेट्रिक क्षमताओं वाले स्मार्टफोन (यानी Apple FaceId/TouchId)',
        'enhance_security_using_webauthn' => 'आप WebAuthn प्रमाणीकरण सक्षम करके अपने 2FAuth खाते की सुरक्षा बढ़ा सकते हैं।<br /><br />
             WebAuthn आपको जल्दी और अधिक सुरक्षित रूप से साइन इन करने के लिए विश्वसनीय उपकरणों (जैसे Yubikeys या बायोमेट्रिक क्षमताओं वाले स्मार्टफोन) का उपयोग करने की सुविधा देता है।',
        'use_security_device_to_sign_in' => 'अपने सुरक्षा उपकरणों में से किसी एक का उपयोग करके प्रमाणित करने के लिए तैयार हो जाइए। अपनी चाबी प्लग इन करें, फेस मास्क या दस्ताने आदि हटा दें।',
        'lost_your_device' => 'क्या आपका उपकरण खो गया?',
        'recover_your_account' => 'अपना खाता पुनः प्राप्त करें',
        'account_recovery' => 'खाता पुनर्प्राप्ति',
        'recovery_punchline' => '2FAuth आपको इस ईमेल पते पर एक पुनर्प्राप्ति लिंक भेजेगा। प्राप्त ईमेल में लिंक पर क्लिक करें और निर्देशों का पालन करें।<br /><br />सुनिश्चित करें कि आप ईमेल को उस डिवाइस पर खोलें जो आपकी अपनी है।',
        'send_recovery_link' => 'रिकवरी लिंक भेजें',
        'account_recovery_email_sent' => 'खाता पुनर्प्राप्ति ईमेल भेज दिया गया है!',
        'disable_all_security_devices' => 'सभी सुरक्षा उपकरण अक्षम (डिसेबल) करें',
        'disable_all_security_devices_help' => 'आपके सभी सुरक्षा उपकरण निरस्त कर दिये जायेंगे। यदि आपने कोई खो दिया है या उसकी सुरक्षा से छेड़छाड़ की गई है तो इस विकल्प का उपयोग करें।',
        'register_a_new_device' => 'एक नई डिवाइस रजिस्टर करें',
        'register_a_device' => 'एक डिवाइस रजिस्टर करें',
        'device_successfully_registered' => 'डिवाइस सफलतापूर्वक पंजीकृत हो गई है!',
        'device_revoked' => 'डिवाइस सफलतापूर्वक निरस्त हो गई है!',
        'revoking_a_device_is_permanent' => 'किसी डिवाइस को रद्द करना स्थायी होता है',
        'recover_account_instructions' => 'आपके खाते को पुनर्प्राप्त करने के लिए, 2FAuth कुछ Webauthn सेटिंग्स को रीसेट करता है ताकि आप अपने ईमेल और पासवर्ड का उपयोग करके साइन इन कर सकें।',
        'invalid_recovery_token' => 'अमान्य पुनर्प्राप्ति टोकन',
        'webauthn_login_disabled' => 'Webauthn लॉगिन अक्षम किया गया है',
        'invalid_reset_token' => 'यह रीसेट टोकन अमान्य है',
        'rename_device' => 'डिवाइस का नाम बदलें',
        'my_device' => 'मेरी डिवाइस',
        'unknown_device' => 'अज्ञात डिवाइस',
        'use_webauthn_only' => [
            'label' => 'केवल WebAuthn का उपयोग करें',
            'help' => 'WebAuthn को अपने 2FAuth खाते में लॉग इन करने का एकमात्र अधिकृत तरीका बनाएं। WebAuthn की बेहतर सुरक्षा का लाभ उठाने के लिए यह अनुशंसित सेटअप है।<br /><br />
                 डिवाइस खो जाने की स्थिति में, आप इस विकल्प को रीसेट करके और अपने ईमेल और पासवर्ड का उपयोग करके साइन इन करके अपना खाता पुनर्प्राप्त कर पाएंगे।<br /><br />
                 ध्यान रहे! इस विकल्प के सक्षम होने के बावजूद ईमेल और पासवर्ड फॉर्म उपलब्ध रहता है, लेकिन वह हमेशा \'प्रमाणीकरण विफल\' प्रतिक्रिया देगा।'
        ],
        'need_a_security_device_to_enable_options' => 'निम्नलिखित विकल्पों को सक्षम करने के लिए कम से कम एक डिवाइस सेट करें',
        'options' => 'विकल्प',
    ],
    'forms' => [
        'name' => 'नाम',
        'login' => 'लॉग इन करें',
        'webauthn_login' => 'WebAuthn लॉगिन',
        'email' => 'ईमेल',
        'password' => 'पासवर्ड',
        'reveal_password' => 'पासवर्ड प्रकट करें',
        'hide_password' => 'पासवर्ड छिपाएं',
        'confirm_password' => 'पासवर्ड की पुष्टि करें',
        'new_password' => 'नया पासवर्ड',
        'confirm_new_password' => 'नए पासवर्ड की पुष्टि करें',
        'dont_have_account_yet' => 'क्या आपके पास अभी तक अकाउंट नहीं है?',
        'already_register' => 'पहले से ही पंजीकृत?',
        'authentication_failed' => 'प्रमाणीकरण विफल रहा',
        'forgot_your_password' => 'पासवर्ड भूल गए हैं?',
        'request_password_reset' => 'रीसेट कीजिए',
        'reset_your_password' => 'अपना पासवर्ड रीसेट करें',
        'reset_password' => 'पासवर्ड रीसेट करें',
        'disabled_in_demo' => 'डेमो मोड में यह सुविधा अक्षम है',
        'new_password' => 'नया पासवर्ड',
        'current_password' => [
            'label' => 'वर्तमान पासवर्ड',
            'help' => 'यह पुष्टि करने के लिए कि यह आप ही हैं, अपना वर्तमान पासवर्ड भरें'
        ],
        'change_password' => 'पासवर्ड बदलें',
        'send_password_reset_link' => 'पासवर्ड रिसेट करने के लिए लिंक भेजें',
        'password_successfully_reset' => 'पासवर्ड सफलता पूर्वक बदला गया।',
        'edit_account' => 'खाता एडिट करें',
        'profile_saved' => 'प्रोफ़ाइल सफलतापूर्वक अपडेट की गई!',
        'welcome_to_demo_app_use_those_credentials' => '2FAuth डेमो में आपका स्वागत है।<br><br>आप ईमेल पते <strong>demo@2fauth.app</strong> और पासवर्ड <strong>demo</strong> का उपयोग करके कनेक्ट कर सकते हैं।',
        'welcome_to_testing_app_use_those_credentials' => '2FAuth परीक्षण के उदाहरण में आपका स्वागत है।<br><br>ईमेल पता <strong>testing@2fauth.app</strong> और पासवर्ड <strong>password</strong> का उपयोग करें',
        'register_punchline' => '<b>2FAuth</b> में आपका स्वागत है।<br/>आगे जाने के लिए आपको एक खाते की आवश्यकता है, कृपया स्वयं को पंजीकृत करें।',
        'reset_punchline' => '2FAuth आपको इस पते पर एक पासवर्ड रीसेट लिंक भेजेगा। नया पासवर्ड सेट करने के लिए प्राप्त ईमेल में दिए गए लिंक पर क्लिक करें।',
        'name_this_device' => 'इस डिवाइस का नाम बताएं',
        'delete_account' => 'अकाउंट डिलीट करें',
        'delete_your_account' => 'अपना अकाउंट डिलीट करें',
        'delete_your_account_and_reset_all_data' => 'आपका उपयोगकर्ता खाता और साथ ही आपका सभी 2FA डेटा हटा दिया जाएगा। वहां से वापसी का कोई विकल्प नहीं है।',
        'reset_your_password_to_delete_your_account' => 'यदि आपने साइन इन करने, साइन आउट करने के लिए हमेशा SSO का उपयोग किया है तो पासवर्ड प्राप्त करने के लिए रीसेट पासवर्ड सुविधा का उपयोग करें ताकि आप यह फॉर्म भर सकें।',
        'deleting_2fauth_account_does_not_impact_provider' => 'आपके 2FAuth खाते को हटाने से आपके बाहरी SSO खाते पर कोई प्रभाव नहीं पड़ेगा।',
        'user_account_successfully_deleted' => 'उपयोगकर्ता का खाता सफलतापूर्वक हटा दिया गया है',
        'has_lower_case' => 'लोअर केस होना चाहिए',
        'has_upper_case' => 'अपर केस होना चाहिए',
        'has_special_char' => 'स्पेशल कैरेक्टर होना चाहिए',
        'has_number' => 'नंबर होना चाहिए',
        'is_long_enough' => 'कम से कम 8 कैरेक्टर',
        'mandatory_rules' => 'अनिवार्य',
        'optional_rules_you_should_follow' => 'अनुशंसित (अत्यधिक)',
        'caps_lock_is_on' => 'कैप्स लॉक ऑन है',
    ],

];
