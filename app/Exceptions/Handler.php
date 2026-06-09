<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
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
        if ($this->isHttpException($exception)) {
            /** @var HttpExceptionInterface $exception */
            $statusCode = $exception->getStatusCode();

            $viewFactory = app(\Illuminate\Contracts\View\Factory::class);
            $responseFactory = app(\Illuminate\Routing\ResponseFactory::class);

            // 1. Route to Admin specific errors
            if ($request->is('admin') || $request->is('admin/*')) {
                if ($viewFactory->exists("admin.errors.{$statusCode}")) {
                    return $responseFactory->view("admin.errors.{$statusCode}", [
                        'exception' => $exception
                    ], $statusCode);
                }
            }

            // 2. Route to Staff specific errors
            if ($request->is('staff') || $request->is('staff/*')) {
                if ($viewFactory->exists("staff.errors.{$statusCode}")) {
                    return $responseFactory->view("staff.errors.{$statusCode}", [
                        'exception' => $exception
                    ], $statusCode);
                }
            }

            // 3. Fallback to Landing Page / root errors folder
            if ($viewFactory->exists("errors.{$statusCode}")) {
                return $responseFactory->view("errors.{$statusCode}", [
                    'exception' => $exception
                ], $statusCode);
            }
        }

        return parent::render($request, $exception);
    }
}