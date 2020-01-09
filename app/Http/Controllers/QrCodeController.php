<?php

namespace App\Http\Controllers;

use Validator;
use Zxing\QrReader;
use App\Classes\TimedTOTP;
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

        // Check uri validity
        if( !TimedTOTP::get($uri) ) {

            return response()->json([
                'error' => [
                   'qrcode' => 'No valid TOTP resource in this QR code'
                ]
            ], 400);

        }

        $uriChunks = explode('?', $uri);

        foreach(explode('&', $uriChunks[1]) as $option) {
            $option = explode('=', $option);
            $options[$option[0]] = $option[1];
        }

        $account = $service = '';

        $serviceChunks = explode(':', str_replace('otpauth://totp/', '', $uriChunks[0]));

        if( count($serviceChunks) > 1 ) {
            $account = $serviceChunks[1];
        }

        $service = $serviceChunks[0];

        if( strstr( $service, '@') ) {
            $account = $service;
            $service = '';
        }

        if( empty($service) & !empty($options['issuer']) ) {
            $service = $options['issuer'];
        }


        // returned object
        $twofaccount = (object) array(
            'service' => $service,
            'account' => $account,
            'uri' => $uri,
            'icon' => '',
            'options' => $options
        );

        return response()->json($twofaccount, 201);
    }
    
}
