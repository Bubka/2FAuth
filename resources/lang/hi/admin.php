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

    'admin' => 'ऐडमिन',
    'admin_panel' => 'Admin panel',
    'app_setup' => 'ऐप का सेटअप',
    'auth' => 'Auth',
    'registrations' => 'रजिस्ट्रेशन',
    'users' => 'उपयोगकर्ता',
    'users_legend' => 'अपने इंस्टेंस पर पंजीकृत उपयोगकर्ताओं को प्रबंधित करें या नए बनाएं।',
    'admin_settings' => 'ऐड्मिन की सेटिंग',
    'create_new_user' => 'उपयोगकर्ता बनाइये',
    'new_user' => 'नया उपयोगकर्ता',
    'search_user_placeholder' => 'उपयोगकर्ता का नाम, ईमेल इत्यादि...',
    'quick_filters_colons' => 'जल्दी काम करने के लिए फ़िल्टर:',
    'user_created' => 'उपयोगकर्ता सफलतापूर्वक बनाया गया',
    'confirm' => [
        'delete_user' => 'क्या आप वास्तव में इस उपयोगकर्ता को डिलीट करना चाहते हैं? फिर वापस नहीं जा पाएंगे।',
        'request_password_reset' => 'क्या आप वास्तव में इस उपयोगकर्ता का पासवर्ड रिसेट करना चाहते हैं?',
        'purge_password_reset_request' => 'क्या आप वाकई पिछले अनुरोध को रद्द करना चाहते हैं?',
        'delete_account' => 'क्या आप वाकई इस उपयोगकर्ता को हटाना चाहते हैं?',
        'edit_own_account' => 'यह आपका अपना अकाउंट है। क्या आप आश्वस्त हैं?',
        'change_admin_role' => 'इससे इस उपयोगकर्ता की अनुमतियों पर गंभीर प्रभाव पड़ेगा। क्या आप आश्वस्त हैं?',
        'demote_own_account' => 'आप अब ऐडमिनिस्ट्रेटर नहीं रहेंगे। वास्तव में आश्वस्त हैं?'
    ],
    'logs' => 'लॉग',
    'administration_legend' => 'निम्नलिखित सेटिंग ग्लोबल हैं और सभी उपयोगकरताओं पर लागू होंगी।',
    'user_management' => 'उपयोगकर्ता प्रबंधन',
    'oauth_provider' => 'OAuth प्रदाता',
    'account_bound_to_x_via_oauth' => 'यह अकाउंट OAuth के माध्यम से :provider अकाउंट से जुड़ा हुआ है',
    'last_seen_on_date' => ':date तारीख को अंतिम बार देखा गया',
    'registered_on_date' => 'रेजिस्ट्रेशन की तारीख :date',
    'updated_on_date' => 'अपडेट की तारीख :date',
    'access' => 'पहुँच',
    'password_requested_on_t' => 'इस उपयोगकर्ता के लिए एक पासवर्ड रीसेट अनुरोध मौजूद है (अनुरोध :datetime पर भेजा गया) जिसका अर्थ है कि उपयोगकर्ता ने अभी तक अपना पासवर्ड नहीं बदला है लेकिन उसे प्राप्त लिंक अभी भी मान्य है। यह स्वयं उपयोगकर्ता या किसी व्यवस्थापक का अनुरोध हो सकता है।',
    'password_request_expired' => 'इस उपयोगकर्ता के लिए एक पासवर्ड रीसेट अनुरोध मौजूद है पर अब मान्य नहीं है, जिसका अर्थ है कि उपयोगकर्ता ने अपना पासवर्ड समय से नहीं बदला है। यह स्वयं उपयोगकर्ता या किसी व्यवस्थापक का अनुरोध हो सकता है।',
    'resend_email' => 'ईमेल दुबारा भेजें',
    'resend_email_title' => 'पासवर्ड रिसेट की ईमेल उपयोगकर्ता को दोबारा भेजें',
    'resend_email_help' => 'उपयोगकर्ता को नया पासवर्ड रीसेट ईमेल भेजने के लिए <b>ईमेल पुनः भेजें</b> का उपयोग करें ताकि वह एक नया पासवर्ड सेट कर सके। इससे उसका वर्तमान पासवर्ड वैसे ही रहेगा और पिछला कोई भी अनुरोध निरस्त कर दिया जाएगा।',
    'reset_password' => 'पासवर्ड रीसेट करें',
    'reset_password_help' => 'उपयोगकर्ता को पासवर्ड रीसेट ईमेल भेजने से पहले पासवर्ड रीसेट करने के लिए <b>पासवर्ड रीसेट करें</b> का उपयोग करें (यह एक अस्थायी पासवर्ड सेट करेगा) ताकि वह एक नया पासवर्ड सेट कर सके। कोई भी पिछला अनुरोध निरस्त कर दिया जाएगा।',
    'reset_password_title' => 'उपयोगकर्ता का पासवर्ड रिसेट करें',
    'password_successfully_reset' => 'पासवर्ड सफलता पूर्वक बदला गया।',
    'user_has_x_active_pat' => ':count सक्रिय टोकन',
    'user_has_x_security_devices' => ':count सुरक्षा डिवाइस (पास की)',
    'revoke_all_pat_for_user' => 'सभी उपयोगकर्ता टोकन निरस्त्र करें',
    'revoke_all_devices_for_user' => 'सभी उपयोगकर्ता डिवाइसेस निरस्त्र करें',
    'danger_zone' => 'खतरे का क्षेत्र',
    'delete_this_user_legend' => 'उपयोगकर्ता का अकाउंट डिलीट होगा और साथ ही उस का 2FA डेटा भी।',
    'this_is_not_soft_delete' => 'यह कोई सॉफ्ट डिलीट नहीं है, इसमें वापस जाना संभव नहीं है।',
    'delete_this_user' => 'इस उपयोगकर्ता को हटा दें',
    'user_role_updated' => 'उपयोगकर्ता का रोल अपडेट हो गया है',
    'pats_succesfully_revoked' => 'उपयोगकर्ता के PAT सफलतापूर्वक निरस्त्र कर दिए गए हैं',
    'security_devices_succesfully_revoked' => 'उपयोगकर्ता के सुरक्षा उपकरण सफलतापूर्वक निरस्त्र कर दिए गए हैं',
    'variables' => 'वेरिएबल',
    'cache_cleared' => 'कैश साफ कर दिया गया है',
    'cache_optimized' => 'कैश अनुकूलित',
    'check_now' => 'अब जांचें',
    'view_on_github' => 'गिटहब पर देखें',
    'x_is_available' => ':version संस्करण उपलब्ध है!',
    'successful_login_on' => '<span class="light-or-darker">:login_at</span> पर सफल लॉगिन',
    'successful_logout_on' => '<span class="light-or-darker">:login_at</span> पर सफल लॉगआउट',
    'failed_login_on' => '<span class="light-or-darker">:login_at</span> पर असफल लॉगिन',
    'viewed_on' => '<span class="light-or-darker">:login_at</span> पर देखा गया',
    'last_accesses' => 'पिछला प्रवेश',
    'see_full_log' => 'पूरा लॉग देखें',
    'browser_on_platform' => ':platform पर :browser',
    'access_log_has_more_entries' => 'प्रवेश के लॉग में और अधिक एंट्री हैं',
    'access_log_legend_for_user' => ':username का पूरा प्रवेश लॉग',
    'show_last_month_log' => 'पिछले महीने की एंट्री दिखाएं',
    'show_three_months_log' => 'पिछले 3 महीने की एंट्री दिखाएं',
    'show_six_months_log' => 'पिछले 6 महीने की एंट्री दिखाएं',
    'show_one_year_log' => 'पिछले साल की एंट्री दिखाएं',
    'sort_by_date_asc' => 'सबसे कम नई पहले दिखाएं',
    'sort_by_date_desc' => 'सबसे नई पहले दिखाएं',
    'single_sign_on' => 'Single Sign-On (SSO)',
    'database' => 'Database',
    'file_system' => 'File system',
    'storage' => 'Storage',
    'forms' => [
        'use_encryption' => [
            'label' => 'संवेदनशील डेटा को सुरक्षित करें',
            'help' => 'संवेदनशील डेटा, 2FA रहस्य और ईमेल, डेटाबेस में एन्क्रिप्टेड रूप में संग्रहीत किए जाते हैं। अपनी .env फ़ाइल के APP_KEY value का (या संपूर्ण फ़ाइल का) बैकअप लेना सुनिश्चित करें क्योंकि यह एन्क्रिप्शन की कुंजी के रूप में कार्य करता है। इस कुंजी के बिना एन्क्रिप्टेड डेटा को समझने का कोई तरीका नहीं है।',
        ],
        'restrict_registration' => [
            'label' => 'रेजिस्ट्रेशन पर प्रतिबंध लगाएं',
            'help' => 'रेजिस्ट्रेशन केवल सीमित ईमेल पतों के लिए ही उपलब्ध कराएं। दोनों नियमों का एक साथ उपयोग किया जा सकता है। इसका SSO के माध्यम से किए गए रेजिस्ट्रेशन पर कोई प्रभाव नहीं पड़ता है।',
        ],
        'restrict_list' => [
            'label' => 'फिल्टरों की सूची',
            'help' => 'इस सूची में दी गई ईमेल को रेजिस्ट्रेशन की अनुमति दी जाएगी। एक पाइप सिम्बल ("|") से पते अलग करें',
        ],
        'restrict_rule' => [
            'label' => 'फिल्टर करने के नियम',
            'help' => 'इस रेगुलर इक्स्प्रेशन से मेल खाने वाले ईमेल को रेजिस्ट्रेशन की अनुमति दी जाएगी',
        ],
        'disable_registration' => [
            'label' => 'रेजिस्ट्रेशन निष्क्रिय करें',
            'help' => 'नए उपयोगकर्ता रेजिस्ट्रेशन रोकें। जब तक इसे ओवरराइड नहीं किया जाता (नीचे देखें), यह SSO को भी प्रभावित करता है, इसलिए नए उपयोगकर्ता SSO के माध्यम से साइन इन नहीं कर पाएंगे',
        ],
        'enable_sso' => [
            'label' => 'Enable SSO',
            'help' => 'आगंतुकों को सिंगल साइन-ऑन योजना के माध्यम से बाहरी ID का उपयोग करके प्रमाणित करने की अनुमति दें',
        ],
        'use_sso_only' => [
            'label' => 'Use SSO only',
            'help' => 'Make SSO the only available method to log in to 2FAuth. Password login and Webauthn are then disabled for regular users. Administrators are not affected by this restriction.',
        ],
        'keep_sso_registration_enabled' => [
            'label' => 'SSO द्वारा रेजिस्ट्रेशन को सक्रिय रखें',
            'help' => 'नए उपयोगकर्ताओं को SSO के माध्यम से पहली बार साइन इन करने की अनुमति दें जबकि रेजिस्ट्रेशन निष्क्रिय है',
        ],
        'is_admin' => [
            'label' => 'ऐडमिनिस्ट्रेटर है',
            'help' => 'उपयोगकर्ता को ऐडमिनिस्ट्रेटर के अधिकार दें. ऐडमिनिस्ट्रेटर के पास पूरे ऐप, यानी सेटिंग्स और अन्य उपयोगकर्ताओं को प्रबंधित करने की अनुमति है, लेकिन वे उन 2FA के लिए पासवर्ड उत्पन्न नहीं कर सकते हैं जो उनके पास नहीं है।'
        ],
        'test_email' => [
            'label' => 'ईमेल कॉन्फ़िगरेशन परीक्षण',
            'help' => 'अपने इंस्टेंस के ईमेल कॉन्फ़िगरेशन को नियंत्रित करने के लिए एक परीक्षण ईमेल भेजें। कार्यशील कॉन्फ़िगरेशन होना महत्वपूर्ण है, अन्यथा उपयोगकर्ता रीसेट पासवर्ड का अनुरोध नहीं कर पाएंगे।',
            'email_will_be_send_to_x' => 'ईमेल <span class=\'is-family-code has-text-info\'>:email</span> पर भेजा जाएगा',
        ],
        'health_endpoint' => [
            'label' => 'Health endpoint',
            'help' => 'URL you can visit to check the health of this 2FAuth instance. This URL can be used to set up a Docker HEALTHCHECK or a Kubernetes HTTPS Liveness probe.',
        ],
        'cache_management' => [
            'label' => 'कैश का मैनेजमेन्ट',
            'help' => 'कभी-कभी कैश को साफ़ करने की आवश्यकता होती है, उदाहरण के लिए एनवायरनमेंट वेरीएबल में बदलाव या अपडेट के बाद। आप इसे यहां से कर सकते हैं.',
        ],
        'store_icon_to_database' => [
            'label' => 'Store icons to database',
            'help' => 'Uploaded icons are registered in the database in addition to the file system storage, which is then used only as a cache. This makes creating a 2FAuth backup much easier, as only the database has to be backed up.<br /><br />But beware, this may has some drawbacks: The database size may increase significantly if the instance hosts many large icons. It may also affect the application performance because the file system is hit more often to ensure it is synchronised with the database.',
        ],
    ],

];