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

    'hello' => 'नमस्कार',
    'hello_user' => 'हैलो :username,',
    'regards' => 'सम्मान सहित',
    'test_email_settings' => [
        'subject' => '2FAuth की टेस्ट ईमेल',
        'reason' => 'आपको यह ईमेल इसलिए प्राप्त हुआ है क्योंकि आपने अपने 2FAuth इंस्टेंस की ईमेल सेटिंग्स को मान्य करने के लिए एक परीक्षण ईमेल का अनुरोध किया था।',
        'success' => 'बधाई हो! यह काम करता है :)'
    ],
    'new_device' => [
        'subject' => 'Connection to 2FAuth from a new device',
        'resume' => 'एक नया उपकरण आपके 2 Fauth अकाउंट से अभी कनेक्ट हुआ है',
        'connection_details' => 'इस कनेक्शन का ब्योरा इस प्रकार है',
        'recommandations' => 'यदि यह आप थे तो इस अलर्ट को नजरंदाज कर दें। यदि अपने अकाउंट पर कोई संदिग्ध ऐक्टिविटी लगती है तो अपना पासवर्ड तुरंत बदलें।'
    ],
    'failed_login' => [
        'subject' => '2FAuth में नाकाम लॉगिन',
        'resume' => 'आपके 2FAuth अकाउंट में एक नाकाम लॉगिन की कोशिश की गई',
        'connection_details' => 'इस कनेक्शन की कोषसीसह का ब्योरा इस प्रकार है',
        'recommandations' => 'यदि यह आप थे तो इस अलर्ट को नजरंदाज कर दें। यदि और कोशिश नाकाम होती है तो सुरक्षा सेटिंग के बारे में अपने 2FAuth ऐड्मिन से संपर्क करें और इस आक्रमणकारी के विरुद्ध कार्यवाही करें।'
    ],
];