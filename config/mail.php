<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    'outbound_rate_limit_per_minute' => env('MAIL_RATE_LIMIT_PER_MINUTE', 10),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'mail.moshedibles.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME', env('ADMIN_MAIL_USERNAME', 'moshedib@moshedibles.com')),
            'password' => env('MAIL_PASSWORD', env('ADMIN_MAIL_PASSWORD')),
            'timeout' => null,
            'auth_mode' => null,
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', env('ADMIN_MAIL_FROM_ADDRESS', 'moshedib@moshedibles.com')),
                'name' => env('MAIL_FROM_NAME', 'Moshel Edibles'),
            ],
        ],

        // 1. General Admin & System Notifications (moshedib@moshedibles.com)
        'admin' => [
            'transport' => 'smtp',
            'host' => env('ADMIN_MAIL_HOST', env('MAIL_HOST', 'mail.moshedibles.com')),
            'port' => env('ADMIN_MAIL_PORT', env('MAIL_PORT', 587)),
            'encryption' => env('ADMIN_MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
            'username' => env('ADMIN_MAIL_USERNAME', 'moshedib@moshedibles.com'),
            'password' => env('ADMIN_MAIL_PASSWORD', env('MAIL_PASSWORD')),
            'timeout' => null,
            'auth_mode' => null,
            'from' => [
                'address' => env('ADMIN_MAIL_FROM_ADDRESS', 'moshedib@moshedibles.com'),
                'name' => env('ADMIN_MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'Moshel Edibles')),
            ],
        ],

        // 2. Customer Inquiries & Contact Form (info@moshedibles.com)
        'info' => [
            'transport' => 'smtp',
            'host' => env('INFO_MAIL_HOST', env('MAIL_HOST', 'mail.moshedibles.com')),
            'port' => env('INFO_MAIL_PORT', env('MAIL_PORT', 587)),
            'encryption' => env('INFO_MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
            'username' => env('INFO_MAIL_USERNAME', 'info@moshedibles.com'),
            'password' => env('INFO_MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
            'from' => [
                'address' => env('INFO_MAIL_FROM_ADDRESS', 'info@moshedibles.com'),
                'name' => env('INFO_MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'Moshel Edibles')),
            ],
        ],

        // 3. Customer Orders & Invoices (order@moshedibles.com)
        'order' => [
            'transport' => 'smtp',
            'host' => env('ORDER_MAIL_HOST', env('MAIL_HOST', 'mail.moshedibles.com')),
            'port' => env('ORDER_MAIL_PORT', env('MAIL_PORT', 587)),
            'encryption' => env('ORDER_MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
            'username' => env('ORDER_MAIL_USERNAME', 'order@moshedibles.com'),
            'password' => env('ORDER_MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
            'from' => [
                'address' => env('ORDER_MAIL_FROM_ADDRESS', 'order@moshedibles.com'),
                'name' => env('ORDER_MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'Moshel Edibles')),
            ],
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => '/usr/sbin/sendmail -bs',
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'moshedib@moshedibles.com'),
        'name' => env('MAIL_FROM_NAME', 'Moshel Edibles'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Dedicated Department Inboxes
    |--------------------------------------------------------------------------
    */
    'contacts' => [
        'admin' => env('ADMIN_NOTIFICATION_EMAIL', 'moshedib@moshedibles.com'),
        'inquiry' => env('INFO_RECEIVING_EMAIL', 'info@moshedibles.com'),
        'order' => env('ADMIN_ORDER_EMAIL', 'order@moshedibles.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
