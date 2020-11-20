<?php

namespace App\Http\Controllers;

use Zxing\QrReader;
use App\TwoFAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\{QRCode, QROptions};

class QrCodeController extends Controller
{
    /**
     * Return a QR code image
     *
     * @param  App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function show(TwoFAccount $twofaccount)
    {

        $options = new QROptions([
            'quietzoneSize' => 2,
            'scale'         => 8,
        ]);

        $qrcode = new QRCode($options);

        return response()->json(['qrcode' => $qrcode->render($twofaccount->uri)], 200);
    }


    /**
     * Decode an uploaded QR Code image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function decode(Request $request)
    {
        
        // input validation
        $this->validate($request, [
            'qrcode' => 'required|image',
        ]);

        // qrcode analysis
        $path = $request->file('qrcode')->store('qrcodes');
        $qrcode = new QrReader(storage_path('app/' . $path));

        $uri = urldecode($qrcode->text());

        // delete uploaded file
        Storage::delete($path);

        return response()->json(['uri' => $uri], 200);
    }
    
}
