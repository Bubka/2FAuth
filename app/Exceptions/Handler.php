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
        // if (!($exception instanceof ValidationException)) {

        //     if ($exception instanceof \Illuminate\Auth\AuthenticationException) {

        //         $response['message'] = (string)$exception->getMessage();
        //         $response['status_code'] = Response::HTTP_UNAUTHORIZED;

        //     } else if ($exception instanceof HttpException) {

        //         $response['message'] = Response::$statusTexts[$exception->getStatusCode()];
        //         $response['status_code'] = $exception->getStatusCode();

        //     } else if ($exception instanceof ModelNotFoundException) {

        //         $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
        //         $response['status_code'] = Response::HTTP_NOT_FOUND;
        //     }
        //     else {
        //         $response = [
        //             'message' => (string)$exception->getMessage(),
        //             'status_code' => $exception->getStatusCode(),
        //         ];
        //     }

        //     if ($this->isDebugMode()) {
        //         $response['debug'] = [
        //             'exception' => get_class($exception),
        //             'trace' => $exception->getTrace()
        //         ];
        //         // return parent::render($request, $exception);
        //     }

        //     return response()->json($response, $response['status_code']);
        // }

        // return parent::render($request, $exception);
        
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

        return $this->customApiResponse($exception);
    }


    /**
     * Set a specific response payload for commons http error codes
     *
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
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
                break;
        }

        if (env('APP_DEBUG')) {

            $response['debug'] = [
                    // 'exception' => get_class($exception),
                    // 'code' => $exception->getCode(),
                    // 'trace' => $exception->getTrace(),
                ];
        }

        return response()->json($response, $statusCode);
    }
}
