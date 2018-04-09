<?php

return [
    // Host Configuration
    'host' => env('RABBITMQ_HOST', 'lcoalhost'),
    'port' => env('RABBITMQ_PORT', '5672'),
    'vhost' => env('RABBITMQ_VHOST', '/'),
    'login' => env('RABBITMQ_LOGIN', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),

    // Options
    'insist' => env('RABBITMQ_INSIST', false),
    'login_method' => env('RABBITMQ_LOGIN_METHOD', 'AMQPLAIN'),
    'locale' => env('RABBITMQ_LOCALE', 'en_US'),
    'connection_timeout' => env('RABBITMQ_CONNECTION_TIMEOUT', 3.0),
    'read_write_timeout' => env('RABBITMQ_READ_WRITE_TIMEOUT', 3.0),
    'keepalive' => env('RABBITMQ_KEEPALIVE', false),
    'heartbeat' => env('RABBITMQ_HEARTBEAT', 0),

    // SSL Options
    'ca_file' => env('RABBITMQ_CA_FILE', ''),
    'verify_peer' => env('RABBITMQ_VERIFY_PEER', false)
];
