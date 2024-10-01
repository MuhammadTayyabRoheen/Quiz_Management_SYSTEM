<?php

namespace App\Services;

use App\Models\ErrorLog;
use App\Models\ActivityLog;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Log;

class LogService
{
    public static function logError($exception)
    {
        ErrorLog::create([
            'error_message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'stack_trace' => $exception->getTraceAsString(),
        ]);

        Log::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }

    public static function logActivity($action, $userId)
    {
        ActivityLog::create([
            'action' => $action,
            'user_id' => $userId,
        ]);
        
        Log::info("User ID $userId performed action: $action");
    }

    public static function logApi($requestData, $responseData)
    {
        ApiLog::create([
            'request_data' => json_encode($requestData),
            'response_data' => json_encode($responseData),
        ]);

        Log::info('API request', ['request' => $requestData, 'response' => $responseData]);
    }
}
