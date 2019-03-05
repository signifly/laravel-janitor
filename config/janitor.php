<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Janitor Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default janitor driver that should be used
    | by the framework.
    |
    */

    'default' => env('JANITOR_DRIVER'),

    /*
     * The username field to use for authentication.
     */
    'username_field' => 'email',

    /*
    |--------------------------------------------------------------------------
    | Janitor Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure what is provided to the Janitor drivers.
    | It must be an array.
    |
    | Supported Drivers: "jwt", "passport"
    |
    */

    'drivers' => [

        'jwt' => [],

        'passport' => [
            'oauth_token_url' => env('JANITOR_OAUTH_TOKEN_URL', 'http://localhost/oauth/token'),
        ],

    ],

];
