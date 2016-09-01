# Документы

TODO

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
