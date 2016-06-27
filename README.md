# OData client

[![Build Status](https://travis-ci.org/mekras/odata-client.svg?branch=master)](https://travis-ci.org/mekras/odata-client)
[![Coverage Status](https://coveralls.io/repos/github/mekras/odata-client/badge.svg?branch=master)](https://coveralls.io/github/mekras/odata-client?branch=master)

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
$object = $service->sendRequest('GET', '/Categories(1)');
```

## URIs

Special helper `URI` can be used to construct URIs.

Get first 5 Category entries: 

```php
use Mekras\OData\Client\URI\Uri;
// ...

$uri = new Uri();
$uri
    ->collection('Categories');
$uri
    ->options()
    ->top(5);

$object = $service->sendRequest('GET', $uri); // Instance of EntitySet 
```

Get Category with ID 123: 

```php
use Mekras\OData\Client\URI\Uri;
// ...

$uri = new Uri();
$uri
    ->collection('Categories')
    ->item('123');

$object = $service->sendRequest('GET', $uri); // Instance of EntityType
```

## Response

Method `Service::sendRequest()` returns an instance one of the descendants of `ODataValue`:

* `Primitive` — one of the [primitive data types](http://www.odata.org/documentation/odata-version-2-0/overview/#AbstractTypeSystem):
    * `BooleanType`
    * `DateTimeType`
    * `FloatType`
    * `GuidType`
    * `IntegerType`
    * `NullType`
    * `StringType`
* `ComplexType` — [Property of an entry](http://www.odata.org/documentation/odata-version-2-0/overview/#EntityDataModel)
    * `EntityType`
* `ServiceDocument`
* `EntitySet`
