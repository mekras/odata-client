<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\Primitive;
use Mekras\OData\Client\OData;
use Mekras\OData\Client\Tests\TestCase;

/**
 * Tests for Mekras\OData\Client\EDM\Primitive
 */
class PrimitiveTest extends TestCase
{
    /**
     * @param \DOMElement $element
     * @param string      $name
     * @param string      $type
     * @param mixed       $value
     *
     * @dataProvider testImportDataProvider
     */
    public function testImport(\DOMElement $element, $name, $type, $value)
    {
        $primitive = new Primitive($this->createFakeNode(), $element);
        static::assertEquals($name, $primitive->getName());
        static::assertEquals($type, $primitive->getType());
        static::assertEquals($value, $primitive->getValue());
    }

    /**
     * Data provider for "testImport()".
     */
    public function testImportDataProvider()
    {
        $doc = new \DOMDocument();
        $doc->load($this->locateFixture('Properties.xml'));

        return [
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Binary')->item(0),
                'Binary',
                Primitive::BINARY,
                'Foo'
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Boolean')->item(0),
                'Boolean',
                Primitive::BOOLEAN,
                true
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Byte')->item(0),
                'Byte',
                Primitive::BYTE,
                255
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'DateTime1')->item(0),
                'DateTime1',
                Primitive::DATETIME,
                new \DateTime('2013-04-15 05:44:26.567', new \DateTimeZone('+00:00'))
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'DateTime2')->item(0),
                'DateTime2',
                Primitive::DATETIME,
                new \DateTime('2013-04-15 05:44:26.567', new \DateTimeZone('+00:00'))
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Decimal')->item(0),
                'Decimal',
                Primitive::DECIMAL,
                123.456
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Double')->item(0),
                'Double',
                Primitive::DOUBLE,
                123.456
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Guid')->item(0),
                'Guid',
                Primitive::GUID,
                '1f13d502-079a-4d65-9ca4-1c5798504475'
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Int16')->item(0),
                'Int16',
                Primitive::INT16,
                16
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Int32')->item(0),
                'Int32',
                Primitive::INT32,
                32
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Int64')->item(0),
                'Int64',
                Primitive::INT64,
                64
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'SByte')->item(0),
                'SByte',
                Primitive::SBYTE,
                -127
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Single')->item(0),
                'Single',
                Primitive::SINGLE,
                123.456
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'String')->item(0),
                'String',
                Primitive::STRING,
                'Foo'
            ],
            [
                $doc->getElementsByTagNameNS(OData::DATA, 'Null')->item(0),
                'Null',
                Primitive::STRING,
                null
            ]
        ];
    }

    /**
     * Test setting type
     */
    public function testSetType()
    {
        $primitive = new Primitive($this->createFakeNode(), 'Foo');
        $primitive->setType(Primitive::BINARY);
        static::assertEquals(Primitive::BINARY, $primitive->getType());
    }

    /**
     * Test creating new object.
     */
    public function testCreate()
    {
        $primitive = new Primitive($this->createFakeNode(), 'Foo', Primitive::DATETIME);
        $primitive->setValue(new \DateTime('2016-01-02 03:04:05'));

        static::assertEquals(Primitive::DATETIME, $primitive->getType());
        static::assertEquals('2016-01-02 03:04:05', $primitive->getValue()->format('Y-m-d H:i:s'));
    }
}
