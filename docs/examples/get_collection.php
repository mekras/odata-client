<?php
/**
 * This example should be executed in console
 *
 * Requires dev dependencies!
 */
namespace Comindware\Tracker\API\Examples;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Mekras\Atom\Document\FeedDocument;
use Mekras\OData\Client\OData;
use Mekras\OData\Client\URI\Uri;
use Mekras\OData\Client\URI\Filter as F;

require __DIR__ . '/../../vendor/autoload.php';

use Mekras\OData\Client\Service;

$service = new Service(
    'http://services.odata.org/OData/OData.svc/',
    HttpClientDiscovery::find(),
    MessageFactoryDiscovery::find()
);

$uri = new Uri();
$uri->collection('Products');
$uri->options()
    ->filter(F::gte('Price', 15.5))
    ->top(5);

$document = $service->sendRequest(OData::GET, $uri);

if (!$document instanceof FeedDocument) {
    die("Not a feed!\n");
}

$entries = $document->getFeed()->getEntries();
foreach ($entries as $entry) {
    printf("Id: %s\nRelease: %s\n", $entry['ID'], $entry['Price']);
}
