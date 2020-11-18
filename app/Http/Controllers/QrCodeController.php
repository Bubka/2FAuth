<?php

namespace App\Http\Controllers;

use Zxing\QrReader;
use App\TwoFAccount;
use App\Classes\Options;
use Illuminate\Support\Str;
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

        if( Options::get('useBasicQrcodeReader') || $request->inputFormat === 'fileUpload') {

            // The frontend send an image resource of the QR code

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
            // The QR code has been flashed and the URI is already decoded
            $this->validate($request, [
                'uri' => 'required|string',
            ]);

            $uri = $request->uri;
        }

        // return the OTP object
        $twofaccount = new TwoFAccount;
        $twofaccount->uri = $uri;

        // When present, use the imageLink parameter to prefill the icon field
        if( $twofaccount->imageLink ) {

            $chunks = explode('.', $twofaccount->imageLink);
            $hashFilename = Str::random(40) . '.' . end($chunks);

            try {

                Storage::disk('local')->put('imagesLink/' . $hashFilename, file_get_contents($twofaccount->imageLink));

                if( in_array(Storage::mimeType('imagesLink/' . $hashFilename), ['image/png', 'image/jpeg', 'image/webp', 'image/bmp']) ) {
                    if( getimagesize(storage_path() . '/app/imagesLink/' . $hashFilename) ) {

                        Storage::move('imagesLink/' . $hashFilename, 'public/icons/' . $hashFilename);
                        $twofaccount->icon = $hashFilename;
                    }
                }
            }
            catch( Exception $e ) {
                $twofaccount->imageLink = null;
            }
        }

        return response()->json($twofaccount->makeVisible(['uri', 'secret', 'algorithm']), 200);
    }
    
}
