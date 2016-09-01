# Класс DocumentFactory

Класс `Mekras\OData\Client\DocumentFactory` позволяет создавать новые документы OData.
Вместо самостоятельного создания экземпляров этого класса используйте метод
[Service::getDocumentFactory](service.ru.md#getDocumentFactory). 

## createEntityDocument

Создаёт новый документ OData, содержащий незаполненную сущность.

```php
public function createEntityDocument(string $type = null):
                    Mekras\OData\Client\Document\EntryDocument 
```

### Аргументы

- **$type** — Класс сущности. Если аргумент не указан или `null`, то вам надо будет самостоятельно
    задать класс, используя метод `Mekras\OData\Client\Element\Entry::setEntityClass`.

### Возвращаемые значения

Возвращает экземпляр `Mekras\OData\Client\Document\EntryDocument`, содержащий новую (пустую)
сущность.

### Ошибки / исключения

Метод вбрасывает следующие исключения.

- `\Mekras\OData\Client\Exception\LogicException` если функция более низкого уровня, используемая
    для создания документов, возвращает документ не того типа.

### Примеры

- [examples/create_entry.php](examples/create_entry.php)

### См. также

- [Создание объектов](create.ru.md)
