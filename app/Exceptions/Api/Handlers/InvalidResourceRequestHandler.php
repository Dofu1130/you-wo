<?php

namespace App\Exceptions\Api\Handlers;

class InvalidResourceRequestHandler implements ResponsableHandler
{
    /**
     * Render json string into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json(
            [
                'error_detail' => [
                    'description' => 'Resource not found!'
                ]
            ],
            404
        );
    }
}
