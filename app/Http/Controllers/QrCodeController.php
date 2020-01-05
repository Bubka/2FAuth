<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Zxing\QrReader;
use App\TwoFAccount;

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

        if($request->hasFile('qrcode')){

            $path = $request->file('qrcode')->store('qrcodes');

            $qrcode = new QrReader(storage_path('app/' . $path));
            $uri = urldecode($qrcode->text());

            $uriChunks = explode('?', $uri);

            foreach(explode('&', $uriChunks[1]) as $option) {
                $option = explode('=', $option);
                $options[$option[0]] = $option[1];
            }

            $email = $service = '';

            $serviceChunks = explode(':', str_replace('otpauth://totp/', '', $uriChunks[0]));

            if( count($serviceChunks) > 1 ) {
                $email = $serviceChunks[1];
            }

            $service = $serviceChunks[0];

            if( strstr( $service, '@') ) {
                $email = $service;
                $service = '';
            }

            if( empty($service) & !empty($options['issuer']) ) {
                $service = $options['issuer'];
            }

            $twofaccount = (object) array(
                'name' => $service,
                'email' => $email,
                'uri' => $uri,
                'icon' => '',
                'options' => $options
            );

            Storage::delete($path);

            return response()->json($twofaccount, 201);
        }
        else {
            return response()->json('no file in $request', 204);
        }
    }
}
