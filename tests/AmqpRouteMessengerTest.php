<?php

use CmdrSharp\AmqpRouteMessenger\Client;
use CmdrSharp\AmqpRouteMessenger\ClientInterface;

class AmqpRouteMessengerTest extends Orchestra\Testbench\TestCase
{
    /** @var Client */
    protected $client;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = new Client();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return ['CmdrSharp\AmqpRouteMessenger\AmqpRouteMessengerServiceProvider'];
    }

    /** @test */
    public function can_instantiate_connection()
    {
        $connection = $this->client->getConnection();

        $this->assertTrue($connection->isConnected());
    }

    /** @test */
    public function can_declare_exchange()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->client->declareExchange('foobar'));
    }

    /** @test */
    public function can_declare_queue()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->client->declareQueue());
    }

    /** @test */
    public function queue_name_not_empty()
    {
        $this->assertNotEmpty($this->client->getQueue());
    }

    /** @test */
    public function can_bind_queue()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->client->bindQueue('foo-bar-qu-qux'));
    }

    /** @test */
    public function can_publish_message()
    {
        $response = $this->client->publish('foo-bar-qu-qux', 'foobar');

        $this->assertTrue($response);
    }

    /** @test */
    public function can_read_message()
    {
        $response = $this->client->read();

        $this->assertNotEmpty($response);
    }
}
