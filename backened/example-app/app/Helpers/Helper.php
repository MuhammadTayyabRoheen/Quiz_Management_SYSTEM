<?php

namespace App\Helpers;

class Helper
{
    /**
     * Success response method
     * 
     * @param mixed $data
     * @param string|null $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data, $message = null, $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode
        ], $statusCode);
    }

    /**
     * Error response method
     * 
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message, $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode
        ], $statusCode);
    }
}
