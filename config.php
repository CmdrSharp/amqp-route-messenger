<?php

return [
    'rabbitmq_host' => env('RABBITMQ_HOST', 'lcoalhost'),
    'rabbitmq_port' => env('RABBITMQ_PORT', '5672'),
    'rabbitmq_vhost' => env('RABBITMQ_VHOST', '/'),
    'rabbitmq_login' => env('RABBITMQ_LOGIN', 'guest'),
    'rabbitmq_password' => env('RABBITMQ_PASSWORD', 'guest')
];
