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
use Mekras\OData\Client\OData;
use Mekras\OData\Client\URI\Uri;

require __DIR__ . '/../../vendor/autoload.php';

use Mekras\OData\Client\Service;

$service = new Service(
    'http://services.odata.org/OData/OData.svc/',
    HttpClientDiscovery::find(),
    MessageFactoryDiscovery::find()
);

$uri = new Uri();
$uri
    ->collection('Products')
    ->item(1);

$document = $service->sendRequest(OData::GET, $uri);

if (!$document instanceof EntryDocument) {
    die("Not an entry!\n");
}

$entry = $document->getEntry();
printf("Id: %s\nRelease: %s\n", $entry['ID'], $entry['Price']);
