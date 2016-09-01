# Создание объектов

## Создание сущности

Пример: [examples/create_entry.php](examples/create_entry.php).

Создание новой сущности состоит из трёх шагов:

1. создание документа-контейнера;
2. заполнение свойств;
3. отправка запроса службе OData.

```php
use Mekras\OData\Client\Service;

$service = new Service($rootUri, $httpClient, $requestFactory); 
// Create container document. "ODataDemo.Product" — entity type we want to create.
$document = $service->getDocumentFactory()->createEntityDocument('ODataDemo.Product');
```

Как создавать экземпляры `Service` см. [Введение](intro.ru.md). Методу
[DocumentFactory::createEntityDocument](document_factory.ru.md#createEntityDocument) указываем
класс создаваемой сущности («ODataDemo.Product»), в ответ получаем документ, содержащий новую, ещё
не заполненную сущность.

```php
use Mekras\OData\Client\Document\EntryDocument;
use Mekras\OData\Client\EDM\Primitive;

$entry = $document->getEntry();
$entry->addTitle('Foo');
$entry->getProperties()->add('ID', mt_rand(50, 10000), Primitive::INT32);
$entry->getProperties()->add('ReleaseDate', new \DateTime(), Primitive::DATETIME);
$entry->getProperties()->add('Rating', 4, Primitive::INT16);
$entry->getProperties()->add('Price', 14.5, Primitive::DOUBLE);
```

Свойства сущности делятся на две группы: свойства Atom и произвольные свойства OData. Первые
устанавливаются при помощи методов `add*` (подробнее см. документацию по
[mekras/atom](https://github.com/mekras/atom/blob/master/docs/03-creating_documents.md)), вторые
с помощью метода `Mekras\OData\Client\Element\Entry::getProperties()`.


```php
use Mekras\OData\Client\OData;
use Mekras\OData\Client\URI\Uri;

$uri = new Uri();
$uri->collection('Products');

$service->sendRequest(OData::CREATE, $uri, $document);
```

Здесь мы отправляем запрос на создание (`OData::CREATE`) сущности в коллекции «Products».

### Связи с другими сущностями

TODO
