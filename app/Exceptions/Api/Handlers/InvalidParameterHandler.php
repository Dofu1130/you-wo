<?php

namespace App\Exceptions\Api\Handlers;

use Illuminate\Validation\ValidationException;

class InvalidParameterHandler implements ResponsableHandler
{
    private $exception;

    public function __construct(ValidationException $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Render json string into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json(
            [
                'error_detail' =>  $this->exception->validator->errors()->getMessages()
            ],
            422
        );
    }
}
