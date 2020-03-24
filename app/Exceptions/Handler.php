<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
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
        if ( $request->wantsJson() ) {

            return $this->handleApiException($request, $exception);

        } else {

           return parent::render($request, $exception);
        }
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException($request, Exception $exception)
    {
        $debug = [
            'exception' => get_class($exception),
            'trace' => $exception->getTrace(),
        ];

        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception, $debug);
    }


    /**
     * Set a specific response payload for commons http error codes
     *
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function customApiResponse($exception, $debug)
    {
        $statusCode = 500;

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }

        $response = [];
        $response['status_code'] = $statusCode;

        switch ($statusCode) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;

            case 403:
                $response['message'] = 'Forbidden';
                break;

            case 404:
                $response['message'] = 'Not Found';
                break;

            case 405:
                $response['message'] = 'Method Not Allowed';
                break;

            case 422:
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;

            default:
                $response['message'] = ($statusCode >= 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();

                if (config('app.debug')) {
                    $response['originalMessage'] = $exception->getMessage();
                    $response['debug'] = $debug;
                }

                break;
        }

        return response()->json($response, $statusCode);
    }
}
