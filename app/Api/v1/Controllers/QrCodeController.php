<?php

namespace App\Api\v1\Controllers;

use App\Models\TwoFAccount;
use App\Services\QrCodeService;
use App\Services\TwoFAccountService;
use App\Api\v1\Requests\QrCodeDecodeRequest;
use App\Http\Controllers\Controller;


class QrCodeController extends Controller
{
    /**
     * The QR code Service instance.
     */
    protected $qrcodeService;

    /**
     * The TwoFAccount Service instance.
     */
    protected $twofaccountService;


    /**
     * Create a new controller instance.
     *
     * @param \App\Services\QrCodeService  $qrcodeService
     * @param \App\Services\TwoFAccountService $twofaccountService
     * @return void
     */
    public function __construct(QrCodeService $qrcodeService, TwoFAccountService $twofaccountService)
    {
        $this->qrcodeService = $qrcodeService;
        $this->twofaccountService = $twofaccountService;
    }


    /**
     * Show a QR code image
     *
     * @param  App\Models\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TwoFAccount $twofaccount)
    {
        $uri = $this->twofaccountService->getURI($twofaccount);

        return response()->json(['qrcode' => $this->qrcodeService->encode($uri)], 200);
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

        return response()->json(['data' => $this->qrcodeService->decode($file)], 200);
    }
    
}