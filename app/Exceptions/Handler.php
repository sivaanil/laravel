<?php namespace Unified\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        // If there is an HttpException 401, return an unauthorized response
        if ($e instanceof HttpException && $e->getStatusCode() == 401) {
            return new JsonResponse($e->getMessage(), 401);
        }

        // if CSRF token is invalid, redirect to login page with flash error message
        if($e instanceof TokenMismatchException){

            return redirect('/')->with('flash_error', 'A security timeout has occurred. Please log in again to continue.');
        }
        return parent::render($request, $e);
    }

}
