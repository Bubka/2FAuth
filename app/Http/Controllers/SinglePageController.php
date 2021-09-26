<?php

namespace App\Http\Controllers;

use App\Services\SettingServiceInterface;

class SinglePageController extends Controller
{

    /**
     * The Settings Service instance.
     */
    protected SettingServiceInterface $settingService;


    /**
     * Create a new controller instance.
     * 
     */
    public function __construct(SettingServiceInterface $SettingServiceInterface)
    {
        $this->settingService = $SettingServiceInterface;
    }


    /**
     * return the main view
     * @return view
     */
    public function index()
    {
        return view('landing')->with('appSettings', $this->settingService->all()->toJson());
    }
}
