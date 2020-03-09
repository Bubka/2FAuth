<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $settings = DB::table('options')->get();

        return response()->json(['settings' => $settings], 200);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function store(Request $request)
    {
        // Store all setting values
        foreach($request->all() as $opt => $val) {
            option([$opt => $val]);
            $settings[$opt] = option($opt);
        }

        return response()->json(['message' =>  __('settings.forms.setting_saved'), 'settings' => $settings], 200);
    }
}
