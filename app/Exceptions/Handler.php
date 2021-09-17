<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => str_replace('App\\', '', $exception->getModel()).' not found'], 404);
        }
        if ($exception instanceof InvalidOtpParameterException) {
            return response()->json([
                'message' => 'invalid OTP parameters',
                'reason' => [$exception->getMessage()]
            ], 400);
        }
        if ($exception instanceof InvalidQrCodeException) {
            return response()->json([
                'message' => 'not a valid QR code'], 400);
        }
        if ($exception instanceof InvalidSecretException) {
            return response()->json([
                'message' => 'not a valid base32 encoded secret'], 400);
        }
        
        return parent::render($request, $exception);
    }
}