# OData client

[![Build Status](https://travis-ci.org/mekras/odata-client.svg?branch=master)](https://travis-ci.org/mekras/odata-client)

OData client-side library.

[OData Documentation](http://www.odata.org/documentation/).

## Service

First you need to create a Service — representation of the certain OData Service specified by URI:

```php
use Mekras\OData\Client\Service;

$service = new Service(
    'http://example.com/odata/',
    $httpClient, // Http\Client\HttpClient
    $messageFactory // Http\Message\MessageFactory
);
```

With this service you can perform requests:

```php
$response = $service->sendRequest('GET', '/Categories(1)');
```

## URIs

Special helper `URI` can be used to construct URIs:

```php
use Mekras\OData\Client\URI\Uri;
// ...

$uri = new Uri();
$uri
    ->collection('ClientSet');
$uri
    ->options()
    ->top(5);

$response = $service->sendRequest('GET', $uri);
```
