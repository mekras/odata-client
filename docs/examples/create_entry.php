<?php
/**
 * This example should be executed in console
 *
 * Requires dev dependencies!
 */
namespace Comindware\Tracker\API\Examples;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Mekras\OData\Client\Document\EntryDocument;
use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\OData;
use Mekras\OData\Client\URI\Uri;
use Mekras\OData\Client\Service;

require __DIR__ . '/../../vendor/autoload.php';

// For write access to services.odata.org we need not get special URI first
$httpClient = HttpClientDiscovery::find();
$requestFactory = MessageFactoryDiscovery::find();
$response = $httpClient->sendRequest(
    $requestFactory->createRequest(
        'GET',
        'http://services.odata.org/V3/(S(readwrite))/OData/OData.svc/'
    )
);
$rootUri = 'http://services.odata.org' . $response->getHeaderLine('Location');

// Now use obtained URI to create service.
$service = new Service($rootUri, $httpClient, $requestFactory);

// Create container document. "ODataDemo.Product" — entity type we want to create.
$document = $service->getDocumentFactory()->createEntityDocument('ODataDemo.Product');
$entry = $document->getEntry();
$entry->addTitle('Foo');
$entry->getProperties()->add('ID', mt_rand(50, 10000), Primitive::INT32);
$entry->getProperties()->add('ReleaseDate', new \DateTime(), Primitive::DATETIME);
$entry->getProperties()->add('Rating', 4, Primitive::INT16);
$entry->getProperties()->add('Price', 14.5, Primitive::DOUBLE);

$uri = new Uri();
$uri->collection('Products');

// Send request to server.
$document = $service->sendRequest(OData::CREATE, $uri, $document);
if (!$document instanceof EntryDocument) {
    die("Invalid response!\n");
}

// Let see our newly created entity.
$entry = $document->getEntry();
foreach ($entry->getProperties() as $property) {
    $value = $property->getValue();
    if ($value instanceof \DateTimeInterface) {
        $value = $value->format(DATE_RFC2822);
    }
    printf("%s: %s\n", $property->getName(), $value);
}
