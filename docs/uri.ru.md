# Составление адресов

TODO

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
