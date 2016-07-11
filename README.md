# [OData](http://www.odata.org/documentation/) client

[![Latest Stable Version](https://poser.pugx.org/mekras/odata-client/v/stable.png)](https://packagist.org/packages/mekras/odata-client)
[![License](https://poser.pugx.org/mekras/odata-client/license.png)](https://packagist.org/packages/mekras/odata-client)
[![Build Status](https://travis-ci.org/mekras/odata-client.svg?branch=master)](https://travis-ci.org/mekras/odata-client)
[![Coverage Status](https://coveralls.io/repos/github/mekras/odata-client/badge.svg?branch=master)](https://coveralls.io/github/mekras/odata-client?branch=master)

OData client-side library.

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

Get 5 Category entries skipping first 10 entries, reverse sorted by Name: 

```php
use Mekras\OData\Client\URI\Uri;
use Mekras\OData\Client\URI\Options;
// ...

$uri = new Uri();
$uri
    ->collection('Categories');
$uri
    ->options()
    ->top(5)
    ->skip(10)
    ->orderBy('Name', Options::DESC);

$document = $service->sendRequest('GET', $uri); 
```

Get Category with ID 123: 

```php
use Mekras\OData\Client\URI\Uri;
// ...

$uri = new Uri();
$uri
    ->collection('Categories')
    ->item('123');

$document = $service->sendRequest('GET', $uri);
```

## Response

Method `Service::sendRequest()` returns an instance one of documents:

* [AtomPub\ServiceDocument](https://github.com/mekras/atompub/blob/master/src/Document/ServiceDocument.php)
* [MetadataDocument](src/Document/MetadataDocument.php)
* [FeedDocument](src/Document/FeedDocument.php)
* [EntryDocument](src/Document/EntryDocument.php)

## Service Documents

[Service Document](http://www.odata.org/documentation/odata-version-2-0/atom-format/#ServiceDocuments)
lists available collections (which are grouped to *workspaces*).

```php
/** @var ServiceDocument $document */
foreach ($document->getWorkspaces() as $workspace) {
    echo 'Title: ' . $workspace->getTitle() . PHP_EOL;
    foreach ($workspace->getCollections() as $collection) {
        echo '  Title: ' . $collection->getTitle() . PHP_EOL;
        echo '  Href: ' . $collection->getHref() . PHP_EOL;
    }
}
```

## Metadata Documents

[Metadata Document](http://www.odata.org/documentation/odata-version-2-0/overview/#ServiceMetadataDocument)

TODO

## Collections of Entries

Collections represent a set of Entries. In OData, Collections are represented as Atom feeds, with
one Atom entry for each Entry within the Collection.

## Entries

In OData, Entries are represented as Atom entries.

```php
//...

$uri = new Uri();
$uri
    ->collection('Categories')
    ->item('123');

$document = $service->sendRequest('GET', $uri);
if (!$document instanceof EntryDocument) {
    throw new \RuntimeException;
}
$entry = $document->getEntry(); // Instance of Mekras\OData\Client\Element\Entry
```

Entry meta data can be accessed via normal `Mekras\Atom\Element\Entry` methods:

```php
echo 'Title: ' . $entry->getTitle() . PHP_EOL;
echo 'ID: ' . $entry->getId() . PHP_EOL;
echo 'Updated: ' . $entry->getUpdated()->format('d.m.y H:i:s') . PHP_EOL;
```

Entry properties can be accessed via `getContent` method:

```php
$properties = $entry->getContent();
echo '  Id: ' . $properties['Id'] . PHP_EOL;
echo '  Hostname: ' . $properties['HostName'] . PHP_EOL;
echo '  Operations: ' . $properties['Operations'] . PHP_EOL;
```

Or directly as array elements:

```php
echo '  Id: ' . $entry['Id'] . PHP_EOL;
echo '  Hostname: ' . $entry['HostName'] . PHP_EOL;
echo '  Operations: ' . $entry['Operations'] . PHP_EOL;
```
