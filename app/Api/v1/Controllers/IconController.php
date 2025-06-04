<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\IconFetchRequest;
use App\Facades\IconStore;
use App\Facades\LogoLib;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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

        $icon     = $request->file('icon');
        $isStored = $name = false;

        if ($icon instanceof UploadedFile) {
            try {
                if ($content = $icon->get()) {
                    $name     = Helpers::getRandomFilename($icon->extension());
                    $isStored = IconStore::store($name, $content);
                }
            } catch (Exception) {
            }
        }

        return $isStored
                ? response()->json(['filename' => $name], 201)
                : response()->json(['message' => __('errors.file_upload_failed')], 500);
    }

    /**
     * Fetch a logo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(IconFetchRequest $request)
    {
        $validated = $request->validated();

        $icon = LogoLib::driver('tfa')->getIcon($validated['service']);
        
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

        IconStore::delete($icon);

        return response()->json(null, 204);
    }
}
