<?php

namespace AppBackoffice\Exceptions;

use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    protected $internalDontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => $e->getMessage() ? : "Not Found",
                'code' => $e->getCode() ? : Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof ExceptionInterface && $e->getPrevious()) {
            $e = $e->getPrevious();
        }

        // todo:
        if ($e instanceof ValidationException) {
            $logger = app(LoggerInterface::class);
            $logger->error('Validation Errors', [
                'errors' => $e->validator->errors()->all(),
                'request' => [
                    'data' => request()?->all(),
                    'headers' => request()->headers,
                ],
            ]);
        }

        return parent::render($request, $e);
    }
}
