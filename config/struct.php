<?php

return [

    'credentials' => [

        /*
         * The API key from private app credentials.
         */
        'api_key' => env('STRUCT_API_KEY', ''),

        /*
         * The password from private app credentials.
         */
        'base_uri' => env('STRUCT_PASSWORD', ''),
    ],

    'webhooks' => [

        /*
         * The webhook secret provider to use.
         */
        'secret_provider' => \Signifly\Struct\Webhooks\ConfigSecretProvider::class,

        /*
         * The shopify webhook secret.
         */
        'secret' => env('STRUCT_WEBHOOK_SECRET'),

    ],

    'exceptions' => [

        /*
         * Whether to include the validation errors in the exception message.
         */
        'include_validation_errors' => false,

    ],
];
