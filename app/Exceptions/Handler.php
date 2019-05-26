<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Validation\ValidationException as ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (!($exception instanceof ValidationException)) {
            $response = [
                'message' => (string)$exception->getMessage(),
                'status_code' => 400,
            ];

            if ($exception instanceof HttpException) {
                $response['message'] = Response::$statusTexts[$exception->getStatusCode()];
                $response['status_code'] = $exception->getStatusCode();
            } else if ($exception instanceof ModelNotFoundException) {
                $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
                $response['status_code'] = Response::HTTP_NOT_FOUND;
            }

            if ($this->isDebugMode()) {
                $response['debug'] = [
                    'exception' => get_class($exception),
                    'trace' => $exception->getTrace()
                ];
                // return parent::render($request, $exception);
            }

            return response()->json($response, $response['status_code']);
        }

        return parent::render($request, $exception);
    }

    /**
     * Determine if the application is in debug mode.
     *
     * @return Boolean
     */
    public function isDebugMode()
    {
        return (boolean) env('APP_DEBUG');
    }
}
