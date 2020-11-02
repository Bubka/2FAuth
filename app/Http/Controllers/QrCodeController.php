<?php

namespace App\Http\Controllers;

use OTPHP\TOTP;
use OTPHP\Factory;
use Zxing\QrReader;
use App\TwoFAccount;
use chillerlan\QRCode\{QRCode, QROptions};
use App\Classes\Options;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Assert\AssertionFailedException;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    /**
     * Return a QR code image
     *
     * @param  \Illuminate\Http\Request  $request
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
     * Handle uploaded qr code image
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function decode(Request $request)
    {

        if(Options::get('useBasicQrcodeReader')) {

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
        }
        else {

            $this->validate($request, [
                'uri' => 'required|string',
            ]);

            $uri = $request->uri;
        }

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

            $error = \Illuminate\Validation\ValidationException::withMessages([
                'qrcode' => __('errors.response.no_valid_otp')
            ]);

            throw $error;
            
        }
    }
    
}
