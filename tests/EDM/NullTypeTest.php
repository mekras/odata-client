<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\NullType;

/**
 * Tests for Mekras\OData\Client\EDM\NullType
 *
 * @covers Mekras\OData\Client\EDM\NullType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class NullTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new NullType();
        static::assertEquals('Null', $value->getType());
        static::assertNull($value->getValue());
        static::assertEquals('', (string) $value);
    }
}
