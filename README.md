# About
[![Latest Stable Version](https://poser.pugx.org/cmdrsharp/guzzle-api/v/stable)](https://packagist.org/packages/cmdrsharp/guzzle-api)
[![Build Status](https://travis-ci.org/CmdrSharp/guzzle-api.svg?branch=master)](https://travis-ci.org/CmdrSharp/guzzle-api)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CmdrSharp/guzzle-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CmdrSharp/guzzle-api/?branch=master)
[![StyleCI](https://styleci.io/repos/126625030/shield?branch=master)](https://styleci.io/repos/126625030)
[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)

This is an API for GuzzleHTTP. It aims to make using Guzzle a bit more clean and extends reusability.

# Requirements
* PHP 7.1 or higher
* Laravel [5.5](https://laravel.com/docs/5.5) or [5.6](https://laravel.com/docs/5.6)

# Installation
Via composer
```bash
$ composer require cmdrsharp/guzzle-api
```

# Usage
Inject the contract into the class where you need the client:
```php
/**
 * @var RequestClientContract
 */
protected $client;

/**
 * @param ClientContract $client
 */
public function __construct(ClientContract $client)
{
    $this->client = $client;
}
```

You can then use the client by first calling make, to set the base URI - and then populating the request.
The client returns a normal PSR ResponseInterface. This means you interact with the response as you would with any Guzzle response.
```php
$this->client = $this->client->make('https://httpbin.org/');

$this->client->to('get')->withBody([
	'foo' => 'bar'
])->withHeaders([
	'baz' => 'qux'
])->withOptions([
	'allow_redirects' => false
])->asJson()->get();

echo $response->getBody();
echo $response->getStatusCode();
```

Alternatively, you can include both the body, headers and options in a single call.

```php
$response = $this->client->to('get')->with([
    'foo' => 'bar'
], [
    'baz' => 'qux'
], [
    'allow_redirects' => false
])->asFormParams()->get();

echo $response->getBody();
echo $response->getStatusCode();
```

The `asJson()` method will send the data using `json` key in the Guzzle request. (You can use `asFormParams()` to send the request as form params).

# Available methods / Example Usage
```php
// GET
$response = $this->client->to('brotli')->get();

// POST
$response = $this->client->to('post')->withBody([
	'foo' => 'bar'
])->asJson()->post();

// PUT
$response = $this->client->to('put')->withBody([
	'foo' => 'bar'
])->asJson()->put();

// PATCH
$response = $this->client->to('patch')->withBody([
	'foo' => 'bar'
])->asJson()->patch();

// DELETE
$response = $this->client->to('delete?id=1')->delete();


// CUSTOM HEADER
$response = $this->client->to('get')->withHeaders([
	'Authorization' => 'Bearer fooBar'
])->asJson()->get();


// CUSTOM OPTIONS
$response = $this->client->to('redirect/5')->withOptions([
	'allow_redirects' => [
		'max' => 5,
		'protocols' => [
			'http',
			'https'
		]
	]
])->get();
```

# Debugging

Using `debug(bool|resource)` before sending a request turns on Guzzle's debugger, more information about that [here](http://docs.guzzlephp.org/en/stable/request-options.html#debug).

The debugger is turned off after every request, if you need to debug multiple requests sent sequentially you will need to turn on debugging for all of them.

**Example**

```php
$logFile = './guzzle_client_debug_test.log';
$logFileResource = fopen($logFile, 'w+');

$this->client->debug($logFileResource)->to('post')->withBody([
	'foo' => 'random data'
])->asJson()->post();

fclose($logFileResource);
```

This writes Guzzle's debug information to `guzzle_client_debug_test.log`.

# Versioning
This package follows [Explicit Versioning](https://github.com/exadra37-versioning/explicit-versioning).

# Authors
[Dylan DPC](https://github.com/Dylan-DPC)
[CmdrSharp](https://github.com/CmdrSharp)

# Credits
Inspired by [Dylan DPC](https://github.com/Dylan-DPC) - the current 1.0.0.0-release is currently also pulled as 0.4.0 in his repo! It's merely re-committed here so that I can easily adapt it as needed in the future.

# License
[The MIT License (MIT)](LICENSE)
