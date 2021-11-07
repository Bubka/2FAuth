<?php

namespace App\Api\v1\Controllers;

use App\Exceptions\DbEncryptionException;
use App\Services\DbEncryptionService;
use App\Services\SettingServiceInterface;
use App\Api\v1\Requests\SettingStoreRequest;
use App\Api\v1\Requests\SettingUpdateRequest;
use App\Http\Controllers\Controller;


class SettingController extends Controller
{

    /**
     * The Settings Service instance.
     */
    protected SettingServiceInterface $settingService;

    /**
     * The Settings Service instance.
     */
    protected DbEncryptionService $dbEncryptionService;


    /**
     * Create a new controller instance.
     * 
     */
    public function __construct(SettingServiceInterface $SettingServiceInterface, DbEncryptionService $dbEncryptionService)
    {
        $this->settingService = $SettingServiceInterface;
        $this->dbEncryptionService = $dbEncryptionService;
    }


    /**
     * List all settings
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = $this->settingService->all();
        $settingsResources = collect();
        $settings->each(function ($item, $key) use ($settingsResources) {
            $settingsResources->push([
                'key' => $key,
                'value' => $item
            ]);
        });

        // return SettingResource::collection($tata);
        return response()->json($settingsResources->all(), 200);
    }


    /**
     * Display a resource
     *
     * @param string $settingName
     * 
     * @return \App\Http\Resources\TwoFAccountReadResource
     */
    public function show($settingName)
    {
        $setting = $this->settingService->get($settingName);

        if (!$setting) {
            abort(404);
        }

        return response()->json([
            'key' => $settingName,
            'value' => $setting
        ], 200);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function store(SettingStoreRequest $request)
    {
        $validated = $request->validated();

        $this->settingService->set($validated['key'], $validated['value']);

        return response()->json([
            'key' => $validated['key'],
            'value' => $validated['value']
        ], 201);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function update(SettingUpdateRequest $request, $settingName)
    {
        $validated = $request->validated();

        // The useEncryption setting impacts records in DB so we delegate the work to the
        // dedicated db encryption service
        if( $settingName === 'useEncryption')
        {
            try {
                $this->dbEncryptionService->setTo($validated['value']);
            }
            catch(DbEncryptionException $ex) {
                return response()->json([
                    'message' => $ex->getMessage()
                ], 400);
            }
        }
        else $this->settingService->set($settingName, $validated['value']);

        return response()->json([
            'key' => $settingName,
            'value' => $validated['value']
        ], 200);

    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function destroy($settingName)
    {
        $setting = $this->settingService->get($settingName);

        if (is_null($setting)) {
            abort(404);
        }

        $optionsConfig = config('2fauth.options');
        if(array_key_exists($settingName, $optionsConfig)) {
            return response()->json(
                ['message' => 'bad request',
                'reason' => [__('errors.delete_user_setting_only')]
            ], 400);
        }

        $this->settingService->delete($settingName);

        return response()->json(null, 204);
    }

}
