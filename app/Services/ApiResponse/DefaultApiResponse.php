<?php

namespace App\Services\ApiResponse;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait DefaultApiResponse
{
    public function apiResponse($data, $code = 200, $message = null): JsonResponse
    {
        return new JsonResponse([
            'code' => $code,
            'message' => Response::$statusTexts[$code],
            'data' => $data,
        ], $code);
    }
}