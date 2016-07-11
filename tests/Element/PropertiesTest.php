<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\Element;

use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\Element\Properties;
use Mekras\OData\Client\Tests\TestCase;

/**
 * Tests for Mekras\OData\Client\Element\Properties
 *
 * @covers Mekras\OData\Client\Element\Properties
 */
class PropertiesTest extends TestCase
{
    /**
     *
     */
    public function testIterator()
    {
        $document = $this->loadXML('Properties.xml');
        $properties = new Properties($this->createFakeNode(), $document->firstChild);
        $names = [];
        foreach ($properties as $name => $value) {
            $names[] = $name;
            static::assertInstanceOf(Primitive::class, $value);
        }
        static::assertEquals(
            [
                'Binary',
                'Boolean',
                'Byte',
                'DateTime1',
                'DateTime2',
                'Decimal',
                'Double',
                'Guid',
                'Int16',
                'Int32',
                'Int64',
                'SByte',
                'Single',
                'String',
                'Null'
            ],
            $names
        );
    }
}
