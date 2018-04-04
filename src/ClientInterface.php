<?php

namespace CmdrSharp\AmqpRouteMessenger;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

interface ClientInterface
{
    /**
     * @param string $exchange_name
     * @return ClientInterface
     */
    public function declareExchange(string $exchange_name): ClientInterface;

    /**
     * @return ClientInterface
     */
    public function declareQueue(): ClientInterface;

    /**
     * @param string $correlation_id
     * @return ClientInterface
     */
    public function bindQueue(string $correlation_id): ClientInterface;

    /**
     * @param string $correlation_id
     * @param string $message
     * @return bool
     */
    public function publish(string $correlation_id, string $message): bool;

    /**
     * @return string
     */
    public function read(): string;

    /**
     * @return ClientInterface
     */
    public function closeConnection(): ClientInterface;

    /**
     * @return ClientInterface
     */
    public function closeChannel(): ClientInterface;

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection;

    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel;

    /**
     * @return string
     */
    public function getQueue(): string;
}
