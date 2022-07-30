<?php

namespace App\Api\v1\Controllers;

use App\Models\TwoFAccount;
use App\Facades\QrCode;
use App\Api\v1\Requests\QrCodeDecodeRequest;
use App\Http\Controllers\Controller;


class QrCodeController extends Controller
{

    /**
     * Show a QR code image
     *
     * @param  App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TwoFAccount $twofaccount)
    {
        $uri = $twofaccount->getURI();

        return response()->json(['qrcode' => QrCode::encode($uri)], 200);
    }


    /**
     * Decode an uploaded QR Code image
     *
     * @param  \App\Api\v1\Requests\QrCodeDecodeRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decode(QrCodeDecodeRequest $request)
    {
        $file = $request->file('qrcode');

        return response()->json(['data' => QrCode::decode($file)], 200);
    }
    
}