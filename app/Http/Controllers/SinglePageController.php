<?php

namespace App\Http\Controllers;

use App\Services\SettingService;

class SinglePageController extends Controller
{

    /**
     * The Settings Service instance.
     */
    protected SettingService $settingService;


    /**
     * Create a new controller instance.
     * 
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }


    /**
     * return the main view
     * @return view
     */
    public function index()
    {
        return view('landing')->with([
            'appSettings' => $this->settingService->all()->toJson(),
            'lang' => $this->settingService->get('lang')
        ]);
    }
}
