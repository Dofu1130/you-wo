<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Exceptions\Api\Handlers\InvalidParameterHandler;
use App\Exceptions\Api\Handlers\InvalidResourceRequestHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $handler = $this->findExceptionResponseHandler($exception);
        if (!is_null($handler)) {
            return $handler->render();
        }

        return parent::render($request, $exception);
    }

    /**
     * To find response handler from exception.
     *
     * @param Exception|Throwable $exception
     * @return object|null
     */
    private function findExceptionResponseHandler(Throwable $exception)
    {
        // 自定義的錯誤資訊部分，目前僅先使用參數驗證錯誤，但這裡也可以做擴充
        switch (true) {
            case $exception instanceof ValidationException:
                return new InvalidParameterHandler($exception);
            case $exception instanceof ModelNotFoundException:
            case $exception instanceof ResourceNotFoundException:
                return new InvalidResourceRequestHandler();
        }

        return null;
    }
}
