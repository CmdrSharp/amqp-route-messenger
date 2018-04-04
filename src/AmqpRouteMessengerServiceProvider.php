<?php

namespace CmdrSharp\AmqpRouteMessenger;

use Illuminate\Support\ServiceProvider;

class AmqpRouteMessengerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config.php' => config_path('amqproutemessenger.php'),
        ], 'amqproutemessenger-config');
    }

    /**
     * Register any application services.;
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, Client::class);
    }
}
