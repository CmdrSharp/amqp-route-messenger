<?php

namespace CmdrSharp\AmqpRouteMessenger;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

interface ClientInterface
{
    /**
     * @param string $exchange_name
     * @param bool $passive
     * @return ClientInterface
     */
    public function declareExchange(string $exchange_name, bool $passive = false): ClientInterface;

    /**
     * @param bool $passive
     * @return ClientInterface
     */
    public function declareQueue(bool $passive = false): ClientInterface;

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
     * @param int $timeout
     * @return string
     */
    public function read(int $timeout = 0): string;

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
