<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    
     protected function redirectTo($request)
     {
         if (! $request->expectsJson()) {
             // If it's not a JSON request, return a login route for web-based apps
             return route('login');
         }
 
         // For API requests, return a JSON error response
         return response()->json(['message' => 'Unauthorized'], 401);
     }
}
