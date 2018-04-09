<?php

namespace CmdrSharp\AmqpRouteMessenger;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Log;

class Client implements ClientInterface
{
    /** @var AMQPStreamConnection */
    protected $connection;

    /** @var AMQPChannel */
    protected $channel;

    /** @var string */
    protected $exchange_name;

    /** @var string */
    protected $queue_name;

    /** @var mixed */
    private $response;

    /**
     * Factory constructor.
     */
    public function __construct()
    {
        $this->connection = new AMQPSSLConnection(
            config('amqproutemessenger.host', 'localhost'),
            config('amqproutemessenger.port', '5672'),
            config('amqproutemessenger.login', 'guest'),
            config('amqproutemessenger.password', 'guest'),
            config('amqproutemessenger.vhost', '/'),
            $this->createSslContext(),
            [
                'insist' => config('amqproutemessenger.insist', false),
                'login_method' => config('amqproutemessenger.login_method', 'AMQPLAIN'),
                'login_response' => null,
                'locale' => config('amqproutemessenger.locale', 'en_US'),
                'connection_timeout' => config('amqproutemessenger.connection_timeout', 3.0),
                'read_write_timeout' => config('amqproutemessenger.read_write_timeout', 3.0),
                'keepalive' => config('amqproutemessenger.keepalive', false),
                'heartbeat' => config('amqproutemessenger.heartbeat', 0)
            ]
        );
    }

    /**
     * Factory destructor.
     */
    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }

        if ($this->connection && $this->connection->isConnected()) {
            $this->connection->close();
        }
    }

    /**
     * Generates an array of SSL Options if specified.
     *
     * @return array
     */
    private function createSslContext(): array
    {
        $ca_file = config('amqproutemessenger.ca_file', null);
        $verify_peer = config('amqproutemessenger.verify_peer', false);

        if (null !== $ca_file) {
            return [
                'cafile' => $ca_file,
                'verify_peer' => $verify_peer
            ];
        }

        return [];
    }

    /**
     * Declares an Exchange.
     *
     * @param string $exchange_name
     * @param bool $passive
     * @return Client
     */
    public function declareExchange(string $exchange_name, bool $passive = false): ClientInterface
    {
        if (!$this->channel) {
            $this->channel = $this->connection->channel();
        }

        $this->exchange_name = $exchange_name;

        $this->channel->exchange_declare(
            $this->exchange_name,
            'direct',
            $passive,
            false,
            false
        );

        return $this;
    }

    /**
     * Declares a randomly generated queue.
     *
     * @param bool $passive
     * @return Client
     */
    public function declareQueue(bool $passive = false): ClientInterface
    {
        if (!$this->channel) {
            $this->channel = $this->connection->channel();
        }

        list($this->queue_name, , ) = $this->channel->queue_declare(
            "",
            $passive,
            false,
            true,
            false
        );

        return $this;
    }

    /**
     * Binds a queue.
     *
     * @param string $correlation_id
     * @return Client
     * @throws \ErrorException
     */
    public function bindQueue(string $correlation_id): ClientInterface
    {
        if (!$this->channel || !$this->queue_name || !$this->exchange_name) {
            throw new \ErrorException('An exchange has not been defined.');
        }

        $this->channel->queue_bind($this->queue_name, $this->exchange_name, $correlation_id);

        return $this;
    }

    /**
     * Publishes a routed message.
     *
     * @param string $correlation_id
     * @param string $message
     * @return bool
     * @throws \ErrorException
     */
    public function publish(string $correlation_id, string $message): bool
    {
        if (!$this->channel || !$this->exchange_name) {
            throw new \ErrorException('An exchange has not been defined.');
        }

        $this->channel->set_ack_handler(function (AMQPMessage $message) {
            Log::info('AMQP Message ACK');
        });

        $this->channel->set_nack_handler(function (AMQPMessage $message) {
            Log::error('AMQP Message NACK');
        });

        $this->channel->confirm_select();

        $this->channel->basic_publish(new AMQPMessage($message), $this->exchange_name, $correlation_id);
        $this->channel->wait_for_pending_acks();

        return true;
    }

    /**
     * Reads from a routed exchange and returns the response.
     *
     * @return mixed
     * @throws \ErrorException
     */
    public function read(): string
    {
        if (!$this->queue_name || !$this->channel) {
            throw new \ErrorException('A queue, or exchange, have not been defined.');
        }

        $callback = function ($msg) {
            return $this->response = $msg->body;
        };

        $this->channel->basic_consume(
            $this->queue_name,
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        while ($this->response === null && count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        return $this->response;
    }

    /**
     * Closes a connection.
     *
     * @return $this
     */
    public function closeConnection(): ClientInterface
    {
        if ($this->connection->isConnected()) {
            $this->connection->close();
        }

        return $this;
    }

    /**
     * Closes a channel.
     *
     * @return $this
     */
    public function closeChannel(): ClientInterface
    {
        if ($this->channel) {
            $this->channel->close();
        }

        return $this;
    }

    /**
     * Returns the active connection.
     *
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }

    /**
     * Returns the channel.
     *
     * @return mixed
     */
    public function getChannel(): AMQPChannel
    {
        return $this->channel ?: false;
    }

    /**
     * Returns the name of the queue declared.
     *
     * @return mixed
     */
    public function getQueue(): string
    {
        return $this->queue_name ?: false;
    }
}
