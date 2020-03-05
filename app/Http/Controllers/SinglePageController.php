<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinglePageController extends Controller
{

    /**
     * return the main view
     * @return view
     */
    public function index()
    {
        $appSettings = \Illuminate\Support\Facades\DB::table('options')->pluck('value', 'key')->toJson();

        return view('landing')->with('appSettings', $appSettings);
    }
}
