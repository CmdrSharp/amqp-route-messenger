# About
[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)

A client for publishing and listening for routed exchange messages.

# Requirements
* PHP 7.1 or higher
* Laravel [5.5](https://laravel.com/docs/5.5) or [5.6](https://laravel.com/docs/5.6)

# Installation
Via composer
```bash
$ composer require cmdrsharp/amqp-route-messenger
```

After installation, publish the configuration file.

```
php artisan vendor:publish --provider="CmdrSharp\AmqpRouteMessenger\AmqpRouteMessengerServiceProvider"
```

Which will create a `amqproutemessenger.php` file in your Laravel config directory. This will contain bindings for the RabbitMQ Queue Details.
It is recommended to simply define these in your `.env` file. The config file will automatically read from these values.
```
RABBITMQ_HOST=
RABBITMQ_PORT=5672
RABBITMQ_VHOST=/
RABBITMQ_LOGIN=
RABBITMQ_PASSWORD=
```

# Usage
Inject the contract into the class where you need the client:
```php
/**
 * @var ClientInterface
 */
protected $client;

/**
 * @param ClientInterface $client
 */
public function __construct(ClientInterface $client)
{
    $this->client = $client;
}
```

Alternatively, if you don't wish to autoload, you may also require it directly.
```php
use CmdrSharp\AmqpRouteMessenger\Client;

/**
 * @var ClientInterface
 */
protected $client;

public function __construct()
{
    $this->client = new Client();
}
```

## Example: Publish a message with a correlation ID
All we are doing here is declaring which exchange to publish the message to, and then publishing it with a specified correlation ID.
`declareExchange` accepts an optional second parameter which makes it [passive](https://www.rabbitmq.com/amqp-0-9-1-reference.html#exchange.declare.passive)
```php
$instance = $this->client->declareExchange('messages')->publish('correlation_id', 'your message');

// Don't forget to close the channel and connection when done!
$instance->closeChannel()->closeConnection();
```

## Example: Read a message with a correlation ID
It is recommended that the consumer is set up prior to any messages being published. This is to ensure that the queue bindings have been created, so that published messages are routed correctly.
```php
$instance = $this->client->declareExchange('messages')
	->declareQueue()
	->bindQueue('correlation_id');

// Note: Reading does freeze the thread until it receives a message.
$message = $instance->read();

// Again, don't forget to close the channel and connection!
$instance->closeChannel()->closeConnection();
```

## Example: Reading and Publishing put together
```php
$instance = $this->client->declareExchange('messages')
	->declareQueue()
	->bindQueue('correlation_id');

$instance->publish('correlation_id', 'your message');

$message = $instance->read();

$instance->closeChannel()->closeConnection();
```

# Versioning
This package follows [Explicit Versioning](https://github.com/exadra37-versioning/explicit-versioning).

# Authors
[CmdrSharp](https://github.com/CmdrSharp)

# License
[The MIT License (MIT)](LICENSE)