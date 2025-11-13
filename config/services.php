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
    'facebook' => [
        'api_version' => env('FACEBOOK_API_VERSION', 'v24.0'),
        'api_url' => 'https://graph.facebook.com/' . env('FACEBOOK_API_VERSION', 'v24.0'),
        'ad_account_id' => env('FACEBOOK_AD_ACCOUNT_ID'),
        'access_token' => env('FACEBOOK_ACCESS_TOKEN'),
        'app_id' => env('FACEBOOK_APP_ID'),
        'client_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI','https://launxh.com/oauth/facebook'),
        'scopes' => env('FACEBOOK_SCOPES'),

        // OAuth URL getter
        'oauth_url' => function() {
            return sprintf(
                'https://www.facebook.com/v3.2/dialog/oauth?access_type=offline&client_id=%s&redirect_uri=%s&response_type=code&scope=%s&state=',
                config('services.facebook.client_id'),
                urlencode(config('services.facebook.redirect')),
                config('services.facebook.scopes')
            );
        },
    ],
    
    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
