<?php

use PHPUnit\Framework\TestCase;
use CmdrSharp\AmqpRouteMessenger\Client;

class AmqpRouteMessengerTest extends TestCase
{
    /** @var Client */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        
        $this->client = new Client();
    }

    /** @test */
    function can_instantiate_connection()
    {
        $connection = $this->client->getConnection();

        $this->assertTrue($connection->isConnected());
    }
}
