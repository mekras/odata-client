# Класс Service

Класс `Mekras\OData\Client\Service` предоставляет интерфейс для взаимодействия со службой OData.

## __construct

Создаёт экземпляр службы OData.

```php
public function __construct(string $serviceRootUri, Http\Client\HttpClient $httpClient,
                    Http\Message\RequestFactory $requestFactory)
```

### Аргументы

- **$serviceRootUri** — Корневой адрес службы OData.
- **$httpClient** — Клиент HTTP (см. [Установка](install.ru.md)).
- **$requestFactory** — Фабрика запросов HTTP (см. [Установка](install.ru.md)).


## sendRequest

Выполняет запрос к службе OData.

```php
public function sendRequest(string $method, string $uri,
    Mekras\Atom\Document\Document Document $document = null): Mekras\Atom\Document\Document
```

### Аргументы

- **$method** — Метод запроса. Можно использовать обычные имена методов HTTP («GET», «POST») или
  константы класса `Mekras\OData\Client\OData`: `OData::GET`, `OData::CREATE`, `OData::UPDATE` и
  `OData::DELETE`.
- **$uri** — Запрашиваемый URI относительно корневого адреса, указанного в конструкторе.
- **$document** — Документ, который надо отправить на сервер.

### Возвращаемые значения

Возвращает экземпляр одного из дочерних классов `Mekras\Atom\Document\Document`:

- `Mekras\Atom\Document\FeedDocument` — коллекция объектов;
- `Mekras\AtomPub\Document\CategoryDocument` — список категорий;
- `Mekras\AtomPub\Document\ServiceDocument` — сервисный документ;
- `Mekras\OData\Client\Document\EntryDocument` — объект OData;
- `Mekras\OData\Client\Document\MetadataDocument` — мета-данные службы.

Подробнее об этих типах документов читайте в разделе [Документы](documents.ru.md).

### Ошибки / исключения

Метод вбрасывает следующие исключения.

- `\InvalidArgumentException` если тип документа $document не поддерживается.
- `\Mekras\Atom\Exception\RuntimeException` в случае ошибок в XML, полученном от сервера.
- `\Mekras\OData\Client\Exception\ClientErrorException` если сервер сообщает об ошибке клиента.
- `\Mekras\OData\Client\Exception\NetworkException` в случае сетевых проблем.
- `\Mekras\OData\Client\Exception\ServerErrorException` в случае ошибки на стороне сервера.
- `\Mekras\OData\Client\Exception\RuntimeException` в остальных случаях.

### См. также

- [Запросы и ответы](requests.ru.md)


## getServiceRootUri

Возвращает корневой адрес службы OData.

```php
public function getServiceRootUri(): string
```

### Возвращаемые значения

Адрес, переданный в конструкторе `Service`.


## getDocumentFactory

Возвращает фабрику документов.

```php
public function getDocumentFactory(): Mekras\OData\Client\DocumentFactory
```

### См. также

- [Создание объектов](create.ru.md)

