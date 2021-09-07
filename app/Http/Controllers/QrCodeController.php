<?php

namespace App\Http\Controllers;

use App\TwoFAccount;
use App\Services\QrCodeService;
use App\Http\Requests\QrCodeDecodeRequest;


class QrCodeController extends Controller
{
    /**
     * The TwoFAccount Service instance.
     */
    protected $qrcodeService;


    /**
     * Create a new controller instance.
     *
     * @param  QrCodeService  $qrcodeService
     * @return void
     */
    public function __construct(QrCodeService $qrcodeService)
    {
        $this->qrcodeService = $qrcodeService;
    }


    /**
     * Show a QR code image
     *
     * @param  App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function show(TwoFAccount $twofaccount)
    {

        return response()->json(['qrcode' => $this->qrcodeService->encode($twofaccount->uri)], 200);
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