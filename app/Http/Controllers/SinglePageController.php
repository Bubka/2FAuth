<?php

namespace App\Http\Controllers;

use App\Classes\Options;
use Illuminate\Http\Request;

class SinglePageController extends Controller
{

    /**
     * return the main view
     * @return view
     */
    public function index()
    {
        return view('landing')->with('appSettings', Options::get()->toJson());
    }
}
