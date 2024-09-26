<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Log;
use Zxing\ChecksumException;
use Zxing\FormatException;
use Zxing\NotFoundException;
use Zxing\QrReader;

class QrCodeService
{
    /**
     * Encode a string into a QR code image
     *
     * @param  string  $data  The string to encode
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
     * @return string
     */
    public static function decode(\Illuminate\Http\UploadedFile $file)
    {
        $qrcode = app()->make(QrReader::class, [
            'imgSource'  => $file->get(),
            'sourceType' => QrReader::SOURCE_TYPE_BLOB,
        ]);

        $text = $qrcode->text();

        if (! $text) {
            $text = $qrcode->text([
                'TRY_HARDER'         => true,
                'NR_ALLOW_SKIP_ROWS' => 0,
            ]);
        }

        // At this point, if we do not have a text, QR code cannot be detected or decoded
        // so we check the error to provide the user a relevant error message
        if (! $text) {
            switch (get_class($qrcode->getError())) {
                case NotFoundException::class:
                    throw new \App\Exceptions\InvalidQrCodeException(__('errors.cannot_detect_qrcode_in_image'));
                case FormatException::class:
                    throw new \App\Exceptions\InvalidQrCodeException(__('errors.cannot_decode_detected_qrcode'));
                case ChecksumException::class:
                    throw new \App\Exceptions\InvalidQrCodeException(__('errors.qrcode_has_invalid_checksum'));
                default:
                    throw new \App\Exceptions\InvalidQrCodeException(__('errors.no_readable_qrcode'));
            }
        }

        $data = urldecode($text);

        Log::info('QR code decoded');

        return $data;
    }
}
