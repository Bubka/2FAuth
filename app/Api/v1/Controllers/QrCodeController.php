<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\QrCodeDecodeRequest;
use App\Facades\QrCode;
use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;

class QrCodeController extends Controller
{
    /**
     * Show a QR code image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TwoFAccount $twofaccount)
    {
        $this->authorize('view', $twofaccount);

        $uri = $twofaccount->getURI();

        return response()->json(['qrcode' => QrCode::encode($uri)], 200);
    }

    /**
     * Decode an uploaded QR Code image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function decode(QrCodeDecodeRequest $request)
    {
        $file = $request->file('qrcode');

        return $file instanceof \Illuminate\Http\UploadedFile
            ? response()->json(['data' => QrCode::decode($file)], 200)
            : response()->json(['message' => __('error.file_upload_failed')], 500);
    }
}
