<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Get your Midtrans credentials from https://dashboard.midtrans.com
    |
    */

    // Midtrans Server Key (from dashboard)
    // Hardcoded to avoid system environment variable conflict
    'server_key' => 'SB-Mid-server-CxzQIML-OHfc5Q7TUdL07A8k',

    // Midtrans Client Key (from dashboard)
    'client_key' => 'SB-Mid-client-p5wUnL5zYKds3E-A',

    // Midtrans Environment (production or sandbox)
    'is_production' => false,

    // Enable 3DS Secure for credit card transactions
    'is_3ds' => true,

    // Callback URL for Midtrans notifications
    // Use your ngrok URL without project path (e.g., https://abc123.ngrok-free.app/api/midtrans/callback)
    'callback_url' => env('MIDTRANS_CALLBACK_URL', 'https://assuring-quail-real.ngrok-free.app/laravel-sewaan-kost/public/api/midtrans/callback'),

    // Skip SSL verification for development (set to true if having SSL certificate issues)
    'skip_ssl_verification' => true,
];
