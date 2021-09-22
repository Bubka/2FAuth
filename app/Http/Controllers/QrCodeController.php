<?php

namespace App\Http\Controllers;

use App\TwoFAccount;
use App\Services\QrCodeService;
use App\Services\TwoFAccountService;
use App\Http\Requests\QrCodeDecodeRequest;


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
     * @param  App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function show(TwoFAccount $twofaccount)
    {
        $uri = $this->twofaccountService->getURI($twofaccount);

        return response()->json(['qrcode' => $this->qrcodeService->encode($uri)], 200);
    }


    /**
     * Decode an uploaded QR Code image
     *
     * @param  \App\Http\Requests\QrCodeDecodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function decode(QrCodeDecodeRequest $request)
    {
        $file = $request->file('qrcode');

        return response()->json(['data' => $this->qrcodeService->decode($file)], 200);
    }
    
}