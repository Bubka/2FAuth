<?php

namespace App\Services;

use Zxing\QrReader;
use Illuminate\Support\Facades\Log;
use chillerlan\QRCode\{QRCode, QROptions};

class QrCodeService
{
    /**
     * Encode a string into a QR code image
     * 
     * @param string $data The string to encode
     * 
     * @return mixed 
     */
    public static function encode(string $data)
    {
        $options = new QROptions([
            'quietzoneSize' => 2,
            'scale'         => 8,
        ]);

        $qrcode = new QRCode($options);

        Log::info('data encoded to QR code');

        return $qrcode->render($data);
    }


    /**
     * Decode an uploaded QR code image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public static function decode(\Illuminate\Http\UploadedFile $file)
    {
        $qrcode = new QrReader($file->get(), QrReader::SOURCE_TYPE_BLOB);
        $data = urldecode($qrcode->text());

        if(!$data) {
            throw new \App\Exceptions\InvalidQrCodeException;
        }
        
        Log::info('QR code decoded');

        return $data;
    }
}