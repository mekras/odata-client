<?php
/**
 * OData client library
 *
 * @author  Михаил Красильников <m.krasilnikov@yandex.ru>
 * @license MIT
 */
namespace Mekras\OData\Client\Tests\EDM;

use Mekras\OData\Client\EDM\IntegerType;

/**
 * Tests for Mekras\OData\Client\EDM\IntegerType
 *
 * @covers Mekras\OData\Client\EDM\IntegerType
 * @covers Mekras\OData\Client\EDM\Primitive
 * @covers Mekras\OData\Client\EDM\ODataValue
 */
class IntegerTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $value = new IntegerType(123);
        static::assertEquals('Edm.Int64', $value->getType());
        static::assertEquals(123, $value->getValue());
        static::assertEquals('123', (string) $value);
    }
}
