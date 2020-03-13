<?php

namespace App\Http\Controllers\Settings;

use App\Classes\Options;
use Illuminate\Http\Request;
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
        // Store all options
        Options::store($request->all());

        return response()->json(['message' =>  __('settings.forms.setting_saved'), 'settings' => Options::get()], 200);
    }
}
