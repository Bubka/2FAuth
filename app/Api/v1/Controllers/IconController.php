<?php

namespace App\Api\v1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;
use App\Services\LogoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IconController extends Controller
{
    /**
     * Handle uploaded icon image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required|image',
        ]);

        $icon = $request->file('icon');
        $path = $icon instanceof \Illuminate\Http\UploadedFile ? $icon->store('', 'icons') : false;

        return $path
                ? response()->json(['filename' => pathinfo($path)['basename']], 201)
                : response()->json(['message' => __('errors.file_upload_failed')], 500);
    }

    /**
     * Fetch a logo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request, LogoService $logoService)
    {
        $this->validate($request, [
            'service' => 'string|regex:/^[^:]+$/i',
        ]);

        $icon = $logoService->getIcon($request->service);

        return $icon
            ? response()->json(['filename' => $icon], 201)
            : response()->json(null, 204);
    }

    /**
     * delete an icon
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $icon, Request $request)
    {
        // An icon affected to someone else's twofaccount cannot be deleted
        if ($icon && TwoFAccount::where('icon', $icon)->where('user_id', '<>', $request->user()->id)->count() > 0) {
            abort(403, 'unauthorized');
        }

        Storage::disk('icons')->delete($icon);

        return response()->json(null, 204);
    }
}
