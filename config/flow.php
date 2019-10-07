<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flow Credentials
    |--------------------------------------------------------------------------
    |
    | These values set the credentials for the environment that will be used on
    | requests to the API.
    |
    */
    'credentials' => [
      'production' => [
        'key' => env('FLOW_KEY'),
        'secret' => env('FLOW_SECRET'),
      ],
      'sandbox' => [
        'key' => env('FLOW_SANDBOX_KEY'),
        'secret' => env('FLOW_SANDBOX_SECRET'),
      ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Flow server urls
    |--------------------------------------------------------------------------
    |
    | This values set the server url for the environment that will be used on
    | requests to the API.
    |
    */
    'server' => [
      'production' => 'https://www.flow.cl/api/',
      'sandbox' => 'https://sandbox.flow.cl/api/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox mode
    |--------------------------------------------------------------------------
    |
    | This option controls the default state for the sandbox. By default
    | the sandbox es false
    |
    */
    'sandbox' => env('FLOW_SANDBOX', false),

];
