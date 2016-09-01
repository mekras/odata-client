# Введение

Первым делом вам надо создать экземпляр `Service` — представляющий службу OData, заданную
определённым URI.

```php
use Mekras\OData\Client\Service;

$service = new Service(
    'http://example.com/odata/',
    $httpClient, // Http\Client\HttpClient
    $messageFactory // Http\Message\MessageFactory
);
```

Теперь можно выполнить первый запрос к службе:

```php
use Mekras\OData\Client\Document\EntryDocument;
use Mekras\OData\Client\OData;
use Mekras\OData\Client\URI\Uri;

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
```

Полный пример: [examples/get_entry.php](examples/get_entry.php).

Разберём пример подробно.

```php
$uri = new Uri();
$uri
    ->collection('Products')
    ->item(1);
```

Здесь мы составляем адрес запрашиваемого документа. Результат будет соответствовать «/Products(1)».

Подробнее класс `Uri` рассматривается в разделе [Составление адресов](uri.ru.md).

```php
$document = $service->sendRequest(OData::GET, $uri);

if (!$document instanceof EntryDocument) {
    die("Not an entry!\n");
}
```

Запрашиваем у службы OData документ (`OData::GET`) расположенный по составленному нами ранее адресу.
Получив ответ, проверяем, что это документ, содержащий одну запись (`EntryDocument`).

Подробнее метод `sendRequest` рассматривается в разделе [Класс Service](service.ru.md). 

```php
$entry = $document->getEntry();
printf("Id: %s\nRelease: %s\n", $entry['ID'], $entry['Price']);
```

Здесь мы получаем из документа запись и выводим некоторые её свойства. 
