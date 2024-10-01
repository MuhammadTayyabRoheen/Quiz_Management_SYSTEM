<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLogger
{
    public function handle(Request $request, Closure $next)
    {
        // Proceed with the request to get the response
        $response = $next($request);

        // Get request and response data
        $requestData = [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ];

        $responseData = [
            'content' => $response->getContent(),
            'status' => $response->status(),
        ];

        // Log the API data to the database
        ApiLog::create([
            'request_data' => json_encode($requestData),
            'response_data' => json_encode($responseData),
            'status_code' => $response->status(),
            'endpoint' => $request->fullUrl(),
            'ip_address' => $request->ip(),
        ]);

        // Return the response
        return $response;
    }
}
