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

    'service' => 'सर्विस',
    'account' => 'अकाउंट',
    'icon' => 'आइकॉन',
    'icon_to_illustrate_the_account' => 'वह आइकान जो खाते को दर्शाता है',
    'remove_icon' => 'आइकन हटाएं',
    'no_account_here' => 'यहाँ 2FA नहीं है',
    'add_first_account' => 'एक विधि चुनें और अपना पहला अकाउंट जोड़ें',
    'use_full_form' => 'या पूरा फॉर्म उपयोग करें',
    'add_one' => 'एक जोड़ें',
    'show_qrcode' => 'QR कोड दिखाएं',
    'no_service' => '- कोई सर्विस नहीं -',
    'account_created' => 'अकाउंट सफलतापूर्वक बना दिया गया है',
    'account_updated' => 'अकाउंट सफलतापूर्वक अपडेट कर दिया गया है',
    'accounts_deleted' => 'अकाउंट सफलतापूर्वक मिटा दिया गया है',
    'accounts_moved' => 'अकाउंट सफलतापूर्वक स्थानांतरित कर दिया गया है',
    'export_selected_accounts' => 'Export selected accounts',
    'twofauth_export_format' => '2FAuth format',
    'twofauth_export_format_sub' => 'Export data using the 2FAuth json schema',
    'twofauth_export_format_desc' => 'You should prefer this option if you need to create a backup that can be restored. This format takes care of the icons.',
    'twofauth_export_format_url' => 'The schema definition is described here:',
    'twofauth_export_schema' => '2FAuth export schema',
    'otpauth_export_format' => 'otpauth URIs',
    'otpauth_export_format_sub' => 'Export data as a list of otpauth URIs',
    'otpauth_export_format_desc' => 'otpauth URI is the most common format used to exchange 2FA data, for example in the form of a QR code when you enable 2FA on a web site. Select this if you want to switch from 2FAuth.',
    'reveal' => 'प्रकट करें',
    'forms' => [
        'service' => [
            'placeholder' => 'गूगल, ट्विटर, एप्पल',
        ],
        'account' => [
            'placeholder' => 'जॉन डो',
        ],
        'new_account' => 'नया अकाउंट',
        'edit_account' => 'खाता एडिट करें',
        'otp_uri' => 'OTP का URI',
        'scan_qrcode' => 'एक QR कोड स्कैन करें',
        'upload_qrcode' => 'एक QR कोड अपलोड करें',
        'use_advanced_form' => 'उन्नत फॉर्म का उपयोग करें',
        'prefill_using_qrcode' => 'QR कोड का उपयोग कर के पहले से भरें',
        'use_qrcode' => [
            'val' => 'एक QR कोड का उपयोग करें',
            'title' => 'जादू से फॉर्म भरने के लिए एक QR कोड का उपयोग करें',
        ],
        'unlock' => [
            'val' => 'अनलॉक',
            'title' => 'इसे अनलॉक करें (अपने जोखिम पर)',
        ],
        'lock' => [
            'val' => 'लॉक करें',
            'title' => 'लॉक करें',
        ],
        'choose_image' => 'अपलोड',
        'i_m_lucky' => 'अपनी किस्मत आज़माएं',
        'i_m_lucky_legend' => 'The "Try my luck" button tries to get a standard icon from the selected icon collection. The simpler the Service field value, the more likely you are to get the expected icon: Do not append any extension (like ".com"), use the exact name of the service, avoid special chars.',
        'test' => 'जाँच',
        'group' => [
            'label' => 'Group',
            'help' => 'The group to which the account is to be assigned'
        ],
        'secret' => [
            'label' => 'रहस्य',
            'help' => 'आपके सुरक्षा कोड बनाने की कुंजी'
        ],
        'plain_text' => 'साधारण टेक्स्ट',
        'otp_type' => [
            'label' => 'Choose the type of OTP to create',
            'help' => 'समय-आधारित OTP या HMAC-आधारित OTP या स्टीम OTP'
        ],
        'digits' => [
            'label' => 'अंक',
            'help' => 'उत्पन्न सुरक्षा कोड के अंकों की संख्या'
        ],
        'algorithm' => [
            'label' => 'अलगोरिथ्म',
            'help' => 'आपके सुरक्षा कोड को सुरक्षित करने की अलगोरिथ्म'
        ],
        'period' => [
            'label' => 'अवधि',
            'placeholder' => 'डिफ़ॉल्ट 30 है',
            'help' => 'उत्पन्न सुरक्षा कोड की वैधता की अवधि सेकंड में'
        ],
        'counter' => [
            'label' => 'काउन्टर',
            'placeholder' => 'डिफ़ॉल्ट 0 है',
            'help' => 'काउंटर का प्रारंभिक मान',
            'help_lock' => 'काउंटर को संपादित करना जोखिम भरा है क्योंकि आप सेवा के सत्यापन करने वाले सर्वर के साथ खाते को डीसिंक्रोनाइज़ कर सकते हैं। संशोधन सक्रिय करने के लिए लॉक आइकन का उपयोग करें, लेकिन केवल तभी जब आप जानते हों कि आप क्या कर रहे हैं'
        ],
        'image' => [
            'label' => 'चित्र',
            'placeholder' => 'http://...',
            'help' => 'खाता आइकन के रूप में उपयोग करने के लिए बाहरी छवि का URL'
        ],
        'options_help' => 'यदि आप नहीं जानते कि उन्हें कैसे सेट किया जाए तो आप निम्नलिखित विकल्पों को खाली छोड़ सकते हैं। सबसे अधिक उपयोग किए जाने वाले मान लागू किए जाएंगे।',
        'alternative_methods' => 'वैकल्पिक विधियाँ',
        'spaces_are_ignored' => 'अनावश्यक खाली अक्षर अपने आप निकाल दिए जाएंगे'
    ],
    'stream' => [
        'live_scan_cant_start' => 'लाइव स्कैन शुरू नहीं किया जा सकता :(',
        'need_grant_permission' => [
            'reason' => '2FAuth को आप का कैमरा उपयोग करने की अनुमति नहीं है',
            'solution' => 'आपको अपने डिवाइस के कैमरे का उपयोग करने की अनुमति देनी होगी। यदि आपने पहले ही इनकार कर दिया है और आपका ब्राउज़र आपको दोबारा संकेत नहीं देता है, तो कृपया अनुमति देने का तरीका जानने के लिए ब्राउज़र के दस्तावेज़ देखें।',
            'click_camera_icon' => 'यह आमतौर पर ब्राउज़र के एड्रेस बार में या उसके बगल में कटे हुए कैमरा आइकन पर क्लिक करके किया जाता है',
        ],
        'not_readable' => [
            'reason' => 'स्कैनर लोड करने में विफल',
            'solution' => 'क्या कैमरा पहले से ही उपयोग में है? सुनिश्चित करें कि कोई अन्य ऐप आपके कैमरे का उपयोग न करे और पुनः प्रयास करें'
        ],
        'no_cam_on_device' => [
            'reason' => 'इस डिवाइस में कैमरा नहीं है',
            'solution' => 'आप शायद अपना वेबकैम लगाना भूल रहे हैं'
        ],
        'secured_context_required' => [
            'reason' => 'सुरक्षित संदर्भ आवश्यक है',
            'solution' => 'लाइव स्कैन के लिए HTTPS आवश्यक है। यदि आप अपने कंप्यूटर से 2FAuth चलाते हैं, तो localhost के अलावा वर्चुअल होस्ट का उपयोग न करें'
        ],
        'https_required' => 'कैमरा स्ट्रीमिंग के लिए HTTPS आवश्यक है',
        'camera_not_suitable' => [
            'reason' => 'स्थापित कैमरे उपयुक्त नहीं हैं',
            'solution' => 'कृपया दूसरी डिवाइस / कैमरा उपयोग करें'
        ],
        'stream_api_not_supported' => [
            'reason' => 'इस ब्राउज़र में स्ट्रीम API समर्थित नहीं है',
            'solution' => 'आप को एक आधुनिक ब्राउजर का उपयोग करना चाहिए'
        ],
    ],
    'confirm' => [
        'delete' => 'क्या आप वास्तव में यह अकाउंट डिलीट करना चाहते हैं?',
        'cancel' => 'परिवर्तन खो जायेंगे। क्या आपको यकीन है?',
        'discard' => 'क्या आप वास्तव में इस अकाउंट को हटाना चाहते हैं?',
        'discard_all' => 'क्या आप वास्तव में सभी अकाउंट को हटाना चाहते हैं?',
        'discard_duplicates' => 'क्या आप वास्तव में सभी डूप्लिकेट को हटाना चाहते हैं?',
    ],
    'import' => [
        'import' => 'आयात',
        'to_import' => 'आयात',
        'import_legend' => '2FAuth विभिन्न 2FA ऐप्स से डेटा आयात कर सकता है।',
        'import_legend_afterpart' => 'QR कोड या JSON फ़ाइल जैसे माइग्रेशन संसाधन प्राप्त करने के लिए इन ऐप्स की निर्यात करने की सुविधा का उपयोग करें और फिर उसे यहां अपलोड करें।',
        'upload' => 'अपलोड',
        'scan' => 'स्कैन करें',
        'supported_formats_for_qrcode_upload' => 'स्वीकृत: jpg, jpeg, png, bmp, gif, svg, या webp',
        'supported_formats_for_file_upload' => 'स्वीकृत: Plain text, json, 2fas',
        'expected_format_for_direct_input' => 'अपेक्षित: otpauth URI की सूची, एक लाइन पर एक',
        'supported_migration_formats' => 'समर्थित माइग्रेशन फॉर्मैट',
        'qr_code' => 'QR कोड',
        'text_file' => 'टेक्स्ट फ़ाईल',
        'direct_input' => 'सीधे दर्ज करें',
        'plain_text' => 'साधारण टेक्स्ट',
        'parsing_data' => 'डेटा पार्स किया जा रहा है...',
        'issuer' => 'ज़ारीकर्ता',
        'imported' => 'आयात',
        'failure' => 'विफलता',
        'x_valid_accounts_found' => ':count वैध अकाउंट पाए गए',
        'submitted_data_parsed_now_accounts_are_awaiting_import' => 'माइग्रेशन संसाधन में निम्नलिखित 2FA खाते पाए गए। अभी तक उनमें से किसी को भी 2FAuth में नहीं जोड़ा गया है।',
        'use_buttons_to_save_or_discard' => 'उन्हें अपने 2FA संग्रह में स्थायी रूप से सहेजने या त्यागने के लिए उपलब्ध बटनों का उपयोग करें।',
        'import_all' => 'सभी आयात करें',
        'import_this_account' => 'इस अकाउंट को आयात करें',
        'discard_all' => 'सभी को खारिज करें',
        'discard_duplicates' => 'डुप्लिकेट त्यागें',
        'discard_this_account' => 'इस अकाउंट को खारिज करें',
        'generate_a_test_password' => 'परीक्षण के लिए एक पासवर्ड बनाएं',
        'possible_duplicate' => 'बिल्कुल समान डेटा वाला एक खाता पहले से मौजूद है',
        'invalid_account' => '- अवैध अकाउंट -',
        'invalid_service' => '- अवैध सर्विस -',
        'do_not_set_password_or_encryption' => 'जब आप 2FA ऐप से डेटा निर्यात करते हैं तो पासवर्ड सुरक्षा या एन्क्रिप्शन सक्रिय न करें अन्यथा 2FAuth उन्हें समझने में सक्षम नहीं होगा।',
    ],

];