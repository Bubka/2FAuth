<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingStoreRequest;
use App\Http\Requests\SettingUpdateRequest;
use App\Services\SettingServiceInterface;
use Illuminate\Http\Request;
use App\Classes\DbProtection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;


class SettingController extends Controller
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
                'name' => $key,
                'data' => $item
            ]);
        });

        // return SettingResource::collection($tata);
        return response()->json($settingsResources->all(), 200);
    }


    /**
     * Display a resource
     *
     * @param string $name
     * 
     * @return \App\Http\Resources\TwoFAccountReadResource
     */
    public function show($name)
    {
        $setting = $this->settingService->get($name);

        if (!$setting) {
            abort(404);
        }

        return response()->json([
            'name' => $name,
            'data' => $setting
        ], 200);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function store(SettingStoreRequest $request)
    {
        $validated = $request->validated();

        $this->settingService->set($validated['name'], $validated['data']);

        return response()->json([
            'name' => $validated['name'],
            'data' => $validated['data']
        ], 201);
    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function update(SettingUpdateRequest $request, $name)
    {
        $validated = $request->validated();

        $setting = $this->settingService->get($name);

        if (is_null($setting)) {
            abort(404);
        }

        $setting = $this->settingService->set($name, $validated['data']);

        return response()->json([
            'name' => $name,
            'data' => $validated['data']
        ], 200);

        // The useEncryption option impacts the [existing] content of the database.
        // Encryption/Decryption of the data is done only if the user change the value of the option
        // to prevent successive encryption

        if( $request->has('useEncryption'))
        {
            if( $request->useEncryption && !$this->settingService->get('useEncryption') ) {

                // user enabled the encryption
                if( !DbProtection::enable() ) {
                    return response()->json(['message' => __('errors.error_during_encryption')], 400);
                }
            }
            else if( !$request->useEncryption && $this->settingService->get('useEncryption') ) {

                // user disabled the encryption
                if( !DbProtection::disable() ) {
                    return response()->json(['message' => __('errors.error_during_decryption')], 400);
                }
            }
        }

    }


    /**
     * Save options
     * @return [type] [description]
     */
    public function destroy($name)
    {
        $setting = $this->settingService->get($name);

        if (is_null($setting)) {
            abort(404);
        }

        $optionsConfig = config('app.options');
        if(array_key_exists($name, $optionsConfig)) {
            return response()->json(
                ['message' => 'bad request',
                'reason' => [__('errors.delete_user_setting_only')]
            ], 400);
        }

        $this->settingService->delete($name);

        return response()->json(null, 204);
    }

}
