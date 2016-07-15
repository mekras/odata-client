<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests;

use Mekras\Atom\Document\EntryDocument;
use Mekras\OData\Client\DocumentFactory;
use Mekras\OData\Client\Element\Entry;

/**
 * Tests for Mekras\OData\Client\DocumentFactory
 *
 * @covers Mekras\OData\Client\DocumentFactory
 */
class DocumentFactoryTest extends TestCase
{
    public function testCreateEntityDocument()
    {
        $factory = new DocumentFactory();
        /** @var EntryDocument $document */
        $document = $factory->createEntityDocument('Foo');

        static::assertInstanceOf(EntryDocument::class, $document);
        /** @var Entry $entry */
        $entry = $document->getEntry();
        static::assertEquals('Foo', $entry->getEntityType());
    }
}
