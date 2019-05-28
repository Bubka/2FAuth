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
        return view("landing");
    }
}
