<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\ComplexType;
use Mekras\OData\Client\EDM\StringType;

/**
 * Tests for Mekras\OData\Client\EDM\ComplexType
 *
 * @covers Mekras\OData\Client\EDM\ComplexType
 */
class ComplexTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * All items should be an instances of ODataValue.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructBadArguments()
    {
        new ComplexType(['foo' => 'bar']);
    }

    /**
     * Test \ArrayAccess implementation.
     */
    public function testArrayAccess()
    {
        $value = new ComplexType(
            [
                'foo' => new StringType('foo'),
                'bar' => new StringType('bar')
            ]
        );

        static::assertArrayHasKey('foo', $value);
        static::assertArrayHasKey('bar', $value);
        static::assertArrayNotHasKey('baz', $value);
        unset($value['bar']);
        static::assertArrayNotHasKey('bar', $value);
        static::assertEquals('foo', $value['foo']);
    }

    /**
     * Test \Iterator implementation.
     */
    public function testIterator()
    {
        $value = new ComplexType(
            [
                'foo' => new StringType('foo'),
                'bar' => new StringType('bar')
            ]
        );

        foreach ($value as $k => $v) {
            static::assertTrue(in_array($k, ['foo', 'bar'], true));
            static::assertEquals($k, $v);
        }
    }
}
