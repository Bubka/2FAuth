<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception, $request) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        });

        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $exception, $request) {
            return response()->json([
                'message' => 'forbidden',
                'reason'  => $exception->getMessage(),
            ], 403);
        });

        $this->renderable(function (InvalidOtpParameterException $exception, $request) {
            return response()->json([
                'message' => 'invalid OTP parameters',
                'reason'  => [$exception->getMessage()],
            ], 400);
        });

        $this->renderable(function (InvalidQrCodeException $exception, $request) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 400);
        });

        $this->renderable(function (InvalidSecretException $exception, $request) {
            return response()->json([
                'message' => 'not a valid base32 encoded secret', ], 400);
        });

        $this->renderable(function (DbEncryptionException $exception, $request) {
            return response()->json([
                'message' => $exception->getMessage(), ], 400);
        });

        $this->renderable(function (InvalidMigrationDataException $exception, $request) {
            return response()->json([
                'message' => __('error.invalid_x_migration', ['appname' => $exception->getMessage()]),
            ], 400);
        });

        $this->renderable(function (UnsupportedMigrationException $exception, $request) {
            return response()->json([
                'message' => __('error.unsupported_migration'),
            ], 400);
        });

        $this->renderable(function (EncryptedMigrationException $exception, $request) {
            return response()->json([
                'message' => __('error.encrypted_migration'),
            ], 400);
        });

        $this->renderable(function (UndecipherableException $exception, $request) {
            return response()->json([
                'message' => __('error.cannot_decipher_secret'),
            ], 400);
        });

        $this->renderable(function (UnsupportedOtpTypeException $exception, $request) {
            return response()->json([
                'message' => __('error.unsupported_otp_type'),
            ], 400);
        });

        $this->renderable(function (FailedIconStoreDatabaseTogglingException $exception, $request) {
            return response()->json([
                'message' => __('error.failed_icon_store_database_toggling'),
            ], 400);
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $exception, $request) {
            if ($exception->guards() === ['reverse-proxy-guard']) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 407);
            } else {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 401);
            }
        });
    }
}
