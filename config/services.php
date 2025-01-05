<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'firebase' => [
        'auth_domain' => env('misd-56691.firebaseapp.com'),
        'database_url' => env('https://misd-56691-default-rtdb.firebaseio.com'),
        'project_id' => env('misd-56691'),
        'storage_bucket' => env('misd-56691.firebasestorage.app'),
        'messaging_sender_id' => env('769022387351'),
        'app_id' => env('1:769022387351:web:0904cce4b5cdd7ddca75d2'),
        'measurement_id' => env('G-F0P2GGEKW7'),
    ],

];
