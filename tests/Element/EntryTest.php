<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Element;

use Mekras\OData\Client\Element\Entry;
use Mekras\OData\Client\Tests\TestCase;
use Mekras\OData\Client\URI\Uri;

/**
 * Tests for Mekras\OData\Client\Element\Entry
 */
class EntryTest extends TestCase
{
    /**
     * Should be created valid "atom:link" element.
     */
    public function testAddRelationViaObject()
    {
        $resource = new Entry($this->createFakeNode());
        $resource->setEntityType('Foo');
        $resource->addLink('FooSet(123)', 'self');

        $entry = new Entry($this->createFakeNode());
        $entry->addRelation($resource);

        static::assertEquals(
            '<entry>' .
            '<link type="application/atom+xml;type=entry" ' .
            'href="FooSet(123)" ' .
            'rel="http://schemas.microsoft.com/ado/2007/08/dataservices/related/Foo" ' .
            'title="Foo"/>' .
            '</entry>',
            $this->getXML($entry)
        );
    }

    /**
     * Should be created valid "atom:link" element.
     */
    public function testAddRelationViaURI()
    {
        $uri = new Uri();
        $uri->collection('FooSet')->item(123);

        $entry = new Entry($this->createFakeNode());
        $entry->addRelation($uri, 'Foo');

        static::assertEquals(
            '<entry>' .
            '<link type="application/atom+xml;type=entry" ' .
            'href="FooSet(123)" ' .
            'rel="http://schemas.microsoft.com/ado/2007/08/dataservices/related/Foo" ' .
            'title="Foo"/>' .
            '</entry>',
            $this->getXML($entry)
        );
    }
}
