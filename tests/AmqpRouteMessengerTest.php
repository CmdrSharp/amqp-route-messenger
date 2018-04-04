<?php

use CmdrSharp\AmqpRouteMessenger\Client;

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
    function can_instantiate_connection()
    {
        $connection = $this->client->getConnection();

        $this->assertTrue($connection->isConnected());
    }
}
