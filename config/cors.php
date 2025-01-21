<?php

return [
    'paths' => ['api/*', 'create_account', 'sanctum/csrf-cookie'], // Add all routes where CORS is needed
    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, PUT, etc.)
    'allowed_origins' => ['http://localhost:3000', 'http://localhost:3001'], // Allow requests from your React app
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Change to `true` if using cookies or sessions
];

