<?php

namespace App\Services;

use App\TwoFAccount;
use Zxing\QrReader;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\{QRCode, QROptions};

class QrCodeService
{
    /**
     * 
     */
    //private $token;

    public function __construct()
    {
        //$this->token = $otpType === TOTP::create($secret) : HOTP::create($secret);
    }


    /**
     * Encode a string into a QR code image
     * 
     * @param string $data The string to encode
     * 
     * @return mixed 
     */
    public function encode(string $data)
    {
        $options = new QROptions([
            'quietzoneSize' => 2,
            'scale'         => 8,
        ]);

        $qrcode = new QRCode($options);

        return $qrcode->render($data);
    }


    /**
     * Decode an uploaded QR code image
     * 
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function decode(\Illuminate\Http\UploadedFile $file)
    {
        $qrcode = new QrReader($file->get(), QrReader::SOURCE_TYPE_BLOB);
        $data = urldecode($qrcode->text());

        if(!$data) {
            throw new \App\Exceptions\InvalidQrCodeException;
        }

        return $data;
    }
}