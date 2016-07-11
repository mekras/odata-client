<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Document;

use Mekras\Atom\Document\EntryDocument;
use Mekras\OData\Client\DocumentFactory;
use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Tests\TestCase;

/**
 * Tests for Mekras\OData\Document\EntryDocument
 */
class EntryDocumentTest extends TestCase
{
    /**
     * EntryDocument::getEntry should return an instance of OData\Entry
     */
    public function testParse()
    {
        $factory = new DocumentFactory();
        /** @var EntryDocument $document */
        $document = $factory->parseXML($this->loadFixture('EntryDocument.xml'));

        static::assertInstanceOf(EntryDocument::class, $document);
        /** @var Entry $entry */
        $entry = $document->getEntry();
        static::assertInstanceOf(Entry::class, $entry);

        static::assertEquals(
            'http://services.odata.org/OData/OData.svc/Categories(0)',
            $entry->getId()
        );
        static::assertEquals('ODataDemo.Category', $entry->getEntityType());
        static::assertEquals('Categories(0)', (string) $entry->getLink('edit'));
        static::assertEquals('Food', (string) $entry->getTitle());
        static::assertEquals('10.03.10 10:43:51', $entry->getUpdated()->format('d.m.y H:i:s'));
        $content = $entry->getContent();
        static::assertEquals('application/xml', $content->getType());

        static::assertEquals(0, $entry['ID']->getValue());
    }
}
