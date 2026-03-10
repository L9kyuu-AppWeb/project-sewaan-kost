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
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xxx'),

    // Midtrans Client Key (from dashboard)
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-xxx'),

    // Midtrans Environment (production or sandbox)
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Enable 3DS Secure for credit card transactions
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    // Callback URL for Midtrans notifications
    'callback_url' => env('MIDTRANS_CALLBACK_URL', 'https://laravel-sewaan-kost.test/api/midtrans/callback'),
];
