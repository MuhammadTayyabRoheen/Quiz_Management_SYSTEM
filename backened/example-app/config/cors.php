<?php

return [

    'paths' => ['api/*', 'student/submit-form', 'sanctum/csrf-cookie'],  // Combine all paths into one array

    'allowed_methods' => ['*'],  // Allow all HTTP methods (GET, POST, etc.)

    'allowed_origins' => ['http://localhost:3000'],  // Allow your React frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Allow all headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
