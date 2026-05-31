<?php

namespace App\Api\v1\Controllers;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureFlagController extends Controller
{
    /**
     * Display all feature flags.
     */
    public function index(Request $request) : JsonResponse
    {
        $features = [];

        foreach (config('2fauth.features', []) as $feature) {
            $features[] = [
                'name'  => (string) $feature,
                'state' => $this->featureState($feature),
            ];
        }

        return response()->json($features, 200);
    }

    /**
     * Display the specified feature flag.
     */
    public function show(Request $request, string $feature) : JsonResponse
    {
        $features = config('2fauth.features', []);

        if (! in_array($feature, $features, true)) {
            abort(404);
        }

        return response()->json([
            'name'  => $feature,
            'state' => $this->featureState($feature),
        ], 200);
    }

    /**
     * Return 'enabled' or 'disabled' based on the feature's status.
     */
    private function featureState(string $feature) : string
    {
        return $this->isFeatureEnabled($feature) ? 'enabled' : 'disabled';
    }

    /**
     * Determine if a feature is enabled, taking into account both the existence of the feature in the config and the related settings.
     */
    private function isFeatureEnabled(string $feature) : bool
    {
        $featureExists = in_array($feature, config('2fauth.features', []), true);

        switch ($feature) {
            case 'sharing':
                return Settings::get('enableSharing') && $featureExists;
            case 'allUsersSharingScope':
                return Settings::get('enableAllUsersSharingScope') && $featureExists;
            default:
                return $featureExists;
        }
    }
}
