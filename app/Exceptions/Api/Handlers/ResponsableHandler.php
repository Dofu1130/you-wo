<?php

namespace App\Exceptions\Api\Handlers;

interface ResponsableHandler
{
    /**
     * Render json string into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render();
}
