<?php
declare(strict_types=1);

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * Return a successful response with data array
     */
    protected function success(array $result = [], $responseCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'data' => $result,
        ];

        return response()->json($response, $responseCode);
    }

    /**
     * Return an response with errors array
     */
    protected function error(
        string $errorCode = \ErrorCodes::GENERAL_ERROR,
        string $errorMessage = '',
        int $responseCode = Response::HTTP_BAD_REQUEST,
        array $details = []
    ): JsonResponse {
        $response = [
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
            'details' => $details
        ];

        return response()->json($response, $responseCode);
    }
}
