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

        $defaultAppSettings = config('2fauth.settings');

        // When deleting a setting, it may be an original or an additional one:
        // - Additional settings are created by administrators to extend 2FAuth, they are not registered in the laravel config object.
        //   They are not nullable so empty string is not allowed.They only exist in the Options table, so it is possible to delete them.
        // - Original settings are part of 2FAuth, they are registered in the laravel config object with their default value.
        //   When set by an admin, their custom value is stored in the Options table too. Deleting a custom value in the Options table from here
        //   won't delete the setting at all, so we reject all requests that ask for.
        //   But there is an exception with the restrictRule and restrictList settings:
        //     Unlike other settings, these two have to support empty strings. Because the Options table does not allow empty strings,
        //     the only way to set them like so is to restore their original value, an empty string.
        if (array_key_exists($settingName, $defaultAppSettings) && $defaultAppSettings[$settingName] !== '') {
            return response()->json(
                ['message'   => 'bad request',
                    'reason' => [__('error.delete_user_setting_only')],
                ], 400);
        }

        Settings::delete($settingName);

        return response()->json(null, 204);
    }
}
