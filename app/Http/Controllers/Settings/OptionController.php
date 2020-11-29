<?php

namespace App\Http\Controllers\Settings;

use App\Classes\Options;
use Illuminate\Http\Request;
use App\Classes\DbProtection;
use App\Http\Controllers\Controller;

class OptionController extends Controller
{


    /**
     * Get options
     * @return [type] [description]
     */
    public function index()
    {
        // Fetch all setting values
        $settings = Options::get();

        return response()->json(['settings' => $settings], 200);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function store(Request $request)
    {
        // The useEncryption option impacts the [existing] content of the database.
        // Encryption/Decryption of the data is done only if the user change the value of the option
        // to prevent successive encryption

        if( isset($request->useEncryption))
        {
            if( $request->useEncryption && !Options::get('useEncryption') ) {

                // user enabled the encryption
                if( !DbProtection::enable() ) {
                    return response()->json(['message' => __('errors.error_during_encryption'), 'settings' => Options::get()], 400);
                }
            }
            else if( !$request->useEncryption && Options::get('useEncryption') ) {

                // user disabled the encryption
                if( !DbProtection::disable() ) {
                    return response()->json(['message' => __('errors.error_during_decryption'), 'settings' => Options::get()], 400);
                }
            }
        }

        // Store all options
        Options::store($request->all());

        return response()->json(['message' => __('settings.forms.setting_saved'), 'settings' => Options::get()], 200);
    }

}
