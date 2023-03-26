<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\SettingStoreRequest;
use App\Api\v1\Requests\SettingUpdateRequest;
use App\Facades\Settings;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * List all settings
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings          = Settings::all();
        $settingsResources = collect([]);
        $settings->each(function (mixed $item, string $key) use ($settingsResources) {
            $settingsResources->push([
                'key'   => $key,
                'value' => $item,
            ]);
        });

        return response()->json($settingsResources->all(), 200);
    }

    /**
     * Display a setting
     *
     * @param  string  $settingName
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($settingName)
    {
        $setting = Settings::get($settingName);

        if (is_null($setting)) {
            abort(404);
        }

        return response()->json([
            'key'   => $settingName,
            'value' => $setting,
        ], 200);
    }

    /**
     * Store a setting
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SettingStoreRequest $request)
    {
        $validated = $request->validated();

        Settings::set($validated['key'], $validated['value']);

        return response()->json([
            'key'   => $validated['key'],
            'value' => $validated['value'],
        ], 201);
    }

    /**
     * Update a setting
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(SettingUpdateRequest $request, string $settingName)
    {
        $validated = $request->validated();

        Settings::set($settingName, $validated['value']);

        return response()->json([
            'key'   => $settingName,
            'value' => $validated['value'],
        ], 200);
    }

    /**
     * Delete a setting
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $settingName)
    {
        $setting = Settings::get($settingName);

        if (is_null($setting)) {
            abort(404);
        }

        $appSettings = config('2fauth.settings');
        if (array_key_exists($settingName, $appSettings)) {
            return response()->json(
                ['message'   => 'bad request',
                    'reason' => [__('errors.delete_user_setting_only')],
                ], 400);
        }

        Settings::delete($settingName);

        return response()->json(null, 204);
    }
}
