<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\SettingUpdateRequest;
use App\Api\v1\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Get detailed information about the authenticated user
     *
     * @return \App\Api\v1\Resources\UserResource|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * List all preferences
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allPreferences(Request $request)
    {
        $preferences = $request->user()->preferences;
        $jsonPrefs   = collect([]);

        $preferences->each(function (mixed $item, string $key) use ($jsonPrefs) {
            $jsonPrefs->push([
                'key'   => $key,
                'value' => $item,
            ]);
        });

        return response()->json($jsonPrefs->all(), 200);
    }

    /**
     * Display a preference
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPreference(Request $request, string $preferenceName)
    {
        if (! Arr::exists($request->user()->preferences, $preferenceName)) {
            abort(404);
        }

        return response()->json([
            'key'   => $preferenceName,
            'value' => $request->user()->preferences[$preferenceName],
        ], 200);
    }

    /**
     * Save a preference
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPreference(SettingUpdateRequest $request, string $preferenceName)
    {
        if (! Arr::exists($request->user()->preferences, $preferenceName)) {
            abort(404);
        }

        $validated = $request->validated();

        $request->user()['preferences->' . $preferenceName] = $validated['value'];
        $request->user()->save();

        Log::info(sprintf('User ID #%s changed its preference %s to %s', $request->user()->id, var_export($preferenceName, true), var_export($validated['value'], true)));

        return response()->json([
            'key'   => $preferenceName,
            'value' => $request->user()->preferences[$preferenceName],
        ], 201);
    }
}
