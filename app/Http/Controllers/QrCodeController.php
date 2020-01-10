<?php

namespace App\Http\Controllers;

use Validator;
use Zxing\QrReader;
use OTPHP\TOTP;
use OTPHP\Factory;
use Assert\AssertionFailedException;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrCodecontroller extends Controller
{
    /**
     * Handle uploaded qr code image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function decode(Request $request)
    {

        // input validation
        $messages = [
            'qrcode.image' => 'Supported format are jpeg, png, bmp, gif, svg, or webp'
        ];

        $validator = Validator::make($request->all(), [
            'qrcode' => 'required|image',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        // qrcode analysis
        $path = $request->file('qrcode')->store('qrcodes');
        $qrcode = new QrReader(storage_path('app/' . $path));

        $uri = urldecode($qrcode->text());

        // delete uploaded file
        Storage::delete($path);

        // return the OTP object
        try {

            $otp = Factory::loadFromProvisioningUri($uri);

            if(!$otp->getIssuer()) {
                $otp->setIssuer($otp->getLabel());
                $otp->setLabel('');
            }

            // returned object
            $twofaccount = (object) array(
                'service' => $otp->getIssuer(),
                'account' => $otp->getLabel(),
                'uri' => $uri,
                'icon' => '',
                'options' => $otp->getParameters()
            );

            return response()->json($twofaccount, 200);

        }
        catch (AssertionFailedException $exception) {

            return response()->json([
                'error' => [
                   'qrcode' => 'No valid TOTP resource in this QR code'
                ]
            ], 400);

        }
    }
    
}
